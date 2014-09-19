<?php

//define('TEST_ENV_EMAIL', 'gdanialq@gmail.com');
define('DOMAIN', 'localhost');
define('EMAIL', defined('TEST_ENV_EMAIL')? TEST_ENV_EMAIL : 'shawnk@snzeng.com');
define('DB_NAME', 'snzeng');
define('DB_UNAME', 'data');
define('DB_PASS', 'D231564d');
define('PROJECT_IMAGE_NUMBER', 6);
define('DEFAULT_IMAGE', 'img/def.jpg');
define('THANK_YOU', 'Dear Customr,'. "\r\n\r\n" .
                    'We at S&Z Engineering do appretiate your contact. We will get back to you as soon as possible.'. "\r\n\r\n" .
                    'Best regards,'. "\r\n" .
                    'SNZENG'. "\r\n" .
                     EMAIL);
