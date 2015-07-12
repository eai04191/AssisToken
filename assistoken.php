<?php
// Show errors.
ini_set( 'display_errors', 1 );

require 'TwistOAuth.phar';

// Prepare simple wrapper function for htmlspecialchars.
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Set default HTTP status code.
$code = 200;

// Set your timezone.
date_default_timezone_set('Asia/Tokyo');

if (!isset($_POST['SN'], $_POST['PW'], $_POST['CK'], $_POST['CS'])) {
    exit();
} 

try {

    // Generate your TwistOAuth object.
    $to = new TwistOAuth(h($_POST['CK']), h($_POST['CS']));
    $to = $to->renewWithAccessTokenX(h($_POST['SN']), h($_POST['PW']));
    $array = array(
        "CK" => h($_POST['CK']),
        "CS" => h($_POST['CS']),
        "AT" => $to -> ot,
        "AS" => $to -> os
    );
    echo  json_encode($array , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );

} catch (TwistException $e) {

    // Set error message.
    $error = $e->getMessage();

    // Overwrite HTTP status code.
    // The exception code will be zero when it thrown before accessing Twitter, we need to change it into 500.
    $code = $e->getCode() ?: 500;

}

if (isset($error)) {
    $array = array(
        "error" => h($error)
    );
    echo  json_encode($array , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
}

// Send charset and HTTP status code to your browser.
header('Content-Type: application/json; charset=utf-8', true, $code);
header('Access-Control-Allow-Origin: *');
?>

