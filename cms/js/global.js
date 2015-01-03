function popup(data, callback) {
    $('#popup').empty();
    $('#popup').append("<span class='error'>" + data + "</span>");
    $('#popup').append("<span id='confirm' class='button'> Yes</span><span id='notConfirm' class='button'> No</span>");
    $('#confirm').on("click", function() {
        $('#popup').empty();
        callback();
    });
    $('#notConfirm').on("click", function() {
        $('#popup').empty();
        return false;
    });
}
function say(data) {
    $('#popup').empty();
    $('#popup').append("<span class='error'>" + data + "</span>");
}
function checkInput(obj) {
    var arr = $.map(obj, function(val, key) {
        return val;
    });
    flag = true;
    $.each(arr, function(i, d) {
        if (d == '') {
            say("Pleas fill out the form completly!");
            flag = false;
        }
    });
    return flag;
}
function showEdit(id) {
    $("tr#" + id + " td").hide();
    $("tr#" + id + " td.edit-form").show();
}
function pausecomp(millis) {
    var date = new Date();
    var curDate = null;

    do {
        curDate = new Date();
    }
    while (curDate - date < millis);
}
function drop(e) {
    e.stopPropagation();
    e.preventDefault();
    var files = e.dataTransfer.files;
    var i, f;
    var toomuch = false;
    for (i = 0, f = files[i]; i != files.length; ++i) {
        var reader = new FileReader();
        reader.onload = function(e) {
            try {
                var data = e.target.result;
                var time = 2000;
                var projList = new Array();
                /* if binary string, read with type 'binary' */
                var workbook = XLSX.read(data, {type: 'binary'});
                var worksheet = workbook.Sheets.projects || false;
                if (worksheet) {
                    //taking care of view
                    $("#drop_box").html("<i class='fa fa-gear fa-spin fa-2x add right'></i><i class='fa fa-file-excel-o fa-5x add'></i> <span class='add'>Uploading... <span class='progress-container'><span id='progress'></span></span></span>");

                    //geocodeing the addresses
                    var geocoder = new google.maps.Geocoder();
                    function codeAddress(proj, callback) {
                        geocoder.geocode({'address': proj.address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                proj.lat = results[0].geometry.location.lat();
                                proj.lng = results[0].geometry.location.lng();
                                callback(proj);
                            } else {
                                $("#drop_box").html("<p class='error'><i class='fa fa-file-o fa-5x'></i> It is too much for Google to geo-code! Drop the same file again.</p>");
                                console.log(status);
                                throw new Error(status);
                            }
                        });
                    }
                    
                    var ids = new Array();
                    for (var cell in worksheet) {
                        if (cell.indexOf("A") >= 0){
                            ids.push({
                                "id": worksheet[cell]['v'],
                                "rowNumber": cell.substring(1)
                            });
                        }
                    }
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {
                            "inq": "getNewProjects",
                            "ids": ids
                        }
                    }).done(function ( json ) {
                        var msg = JSON.parse(json);
                        for(var i=0 ; i<msg.length ; i++){
                            var m = msg[i];
                            var temp = {
                                "id": worksheet['A'+m]['v'],
                                "name": worksheet['B'+m]['v'],
                                "address": worksheet['C'+m]['v']
                            };
                            projList.push(temp);
                        }
                        var loopTimeout = function(i, max, interval, func) {
                            if (i >= max) {
                                return;
                            }
                            func(i);
                            i++;
                            setTimeout(function() {
                                loopTimeout(i, max, interval, func);
                            }, interval);
                        };
                        var progress = function(cmp, tot){
                            if(cmp === tot-1){
                                $("#progress").css("width", "100%");
                                window.setTimeout(function(){
                                    $("#drop_box").html("<i class='fa fa-thumbs-o-up fa-3x add right'></i><i class='fa fa-file-excel-o fa-5x add'></i> <span class='add'>Saved! <i title='Referesh' class='fa fa-repeat fa-lg edit button' onclick='refereshBox()'></i></span>");
                                }, 1000);
                                return;
                            }
                            var percent = 100/tot*cmp;
                            $("#progress").css("width", percent+"%");
                            console.log(percent);
                        };
                        if(projList.length > 0){
                            loopTimeout(0, projList.length, time, function(i){
                                progress(i, projList.length);
                                codeAddress(projList[i], function(prj) {
                                    addMarkerProject(prj);
                                });
                            });
                        }else{
                                progress(0, 1);
                        }
                    });
                } else {
                    throw("This file does not have 'projects' worksheet! ");
                }
            } catch (e) {
                $("#drop_box").html("<p class='error'><i class='fa fa-file-o fa-5x'></i> " + e + "</p>");
            }
        };
        reader.readAsBinaryString(f);
    }
}
function allowDrop(ev) {
    ev.preventDefault();
}
function addMarkerProject (prj){
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            "inq": "addMarkerPrj",
            "prj": prj
        }
    }).error(function ( json ) {
        console.log(json);
    });
}
function refereshBox(){
        $("#drop_box").fadeOut();
        window.setTimeout(function(){
            $("#drop_box").html(dropBoxContent).fadeIn();
        }, 400); 
}