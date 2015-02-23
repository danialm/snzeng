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
    say("");
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
                var worksheet = workbook.Sheets.Projects || false;
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
                                window.clearInterval(window.TO);
                            }
                        });
                    }
                    
                    var ids = new Array();
                    for (var cell in worksheet) {
                        if (cell.indexOf("A") >= 0 && worksheet[cell].t === 'n' ){ //colomn A with numneric value
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
                            if(worksheet['C'+m] && worksheet['C'+m].t === 's' && worksheet['C'+m].v.trim() !== ''){
                                var temp = {
                                    "id": worksheet['A'+m]['v'],
                                    "name": (worksheet['D'+m] && worksheet['D'+m].t === 's' && worksheet['D'+m].v.trim() !== '') ? worksheet['D'+m]['v'] : "No Name",
                                    "address": worksheet['C'+m]['v']
                                };
                                projList.push(temp);
                            }
                        }
                        var loopTimeout = function(i, max, interval, func) {
                            if (i >= max) {
                                return;
                            }
                            func(i);
                            i++;
                            window.TO = setTimeout(function() {
                                loopTimeout(i, max, interval, func);
                            }, interval);
                        };
                        var progress = function(cmp, tot){
                            if(cmp === tot-1){
                                $("#progress").css("width", "100%");
                                window.setTimeout(function(){
                                    $("#drop_box").html("<i class='fa fa-thumbs-o-up fa-3x add right'></i><i class='fa fa-file-excel-o fa-5x add'></i> <span class='add'>Saved! <i title='Referesh' class='fa fa-repeat fa-lg edit button' onclick='refereshBox(\"excel\")'></i></span>");
                                }, 1000);
                                return;
                            }
                            var percent = 100/tot*cmp;
                            $("#progress").css("width", percent+"%");
                        };
                        if(projList.length > 0){
                            loopTimeout(0, projList.length, time, function(i){
                                progress(i, projList.length);
                                codeAddress(projList[i], function(prj) {
                                    $.ajax({
                                        type: "POST",
                                        url: "ajax.php",
                                        data: {
                                            "inq": "addMarkerPrj",
                                            "prj": prj
                                        }
                                    }).fail(function ( e ) {
                                        console.log(e);
                                        $("#drop_box").html("<p class='error'><i class='fa fa-file-o fa-5x'></i> Server Error! Please wait 1 MINUTE and try the same excel file again.</p>");
                                        window.clearInterval(window.TO);
                                    });
                                });
                            });
                        }else{
                                progress(0, 1);
                        }
                    }).fail(function(e){
                        console.log(e);
                        $("#drop_box").html("<p class='error'><i class='fa fa-file-o fa-5x'></i> Server Error! Please wait 1 MINUTE and try the same excel file again.</p>");
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
function imgDrop(e) {
    say("");
    e.stopPropagation();
    e.preventDefault();
    var images = [],
    error = [],
    files = e.dataTransfer.files;
    for (var i=0; files[i];i++) {
        var file = files[i];
        if (!file.type.match(/image.*/)){
            error.push(file.name+" is not an image.");
            continue;
        }
        if(file.size > 50000){
            error.push(file.name+" is too big.");
            continue;
        }
        images.push(file);
    }
    if(images.length>0){
        //taking care of view
        $("#img_drop_box").html("<i class='fa fa-gear fa-spin fa-2x add right'></i><i class='fa fa-file-image-o fa-5x add'></i> <span class='add'>Uploading... </span>");
        uploadImages(images, function(err){
            if(typeof err === "undefined"){
                $("#img_drop_box").html("<i class='fa fa-thumbs-o-up fa-3x add right'></i><i class='fa fa-file-image-o fa-5x add'></i> <span class='add'>Saved! <i title='Referesh' class='fa fa-repeat fa-lg edit button' onclick='refereshBox(\"img\")'></i></span>");
            }else{
                $("#img_drop_box").html("<p class='error'><i class='fa fa-file-o fa-5x'></i> " + err + "</p>");
            }
        });
    }
    
    if(error.length>0){
        say(" <p class='error'>"+error.join("<br>")+"</p>");
    }

}
function uploadImages(images, callBack){
    var ids = [];
    if(!images.length>0) return callBack("No images!");
    
    var formData = new FormData();
    for(var i=0; images[i]; i++ ){
        var image = images[i];
        var id = parseInt(image.name.slice(0,image.name.indexOf("_"))).toString();
        ids.push({
            "id": id,
            "rowNumber": i.toString()
        });
    }
    $.ajax({
        type: "post",
        url: "ajax.php",
        data: {
            "inq": "getNewProjects",
            "ids": ids
        }
    }).done(function (json) {
        var msgs = JSON.parse(json);
        for(var i=0; images[i]; i++){
            var image = images[i];
            if(msgs.indexOf(i.toString())<0){
                formData.append("img/projects/project"+parseInt(image.name.slice(0,image.name.indexOf("_"))).toString()+".thumb", image);
            }
        }
        $.ajax({
            url: 'ajax.php?inq=addImages',  //Server script to process data
            type: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function(data){
                                if(data === "1"){//success
                                    callBack();
                                }else{//error
                                    callback(data.toString());
                                }
            },
            error: function(){
            },
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    });
}
function allowDrop(ev) {
    ev.preventDefault();
}

function refereshBox(dropBox){
    say("");
    var box = dropBox === 'img' ? $("#img_drop_box") : $("#drop_box");
        box.fadeOut();
        window.setTimeout(function(){
            box.html(dropBoxContent).fadeIn();
        }, 400); 
}