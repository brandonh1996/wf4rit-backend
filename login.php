<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here.
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// check email existence here// get posted data
$data = json_decode(file_get_contents("php://input"));

//$email = $_POST['email'];
$password = hash('sha256', $_GET['password']);


// set product property values
$user->email = $_GET['email'];

$email_exists = $user->emailExists();
 
// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

global $iss;
global $aud;
global $iat;
global $nbf;
global $user;

$salt = "ImCreatingThisSoItsALotHarderToGuess256";

echo $password;

// check if email exists and if password is correct
if($email_exists && password_verify($password, $user->password)){
    $token = array(
       "iss" => $iss, //issuer -->identifies the principle that issued JWT
       "aud" => $aud, //audience --> intended recepient of JWT 
       "iat" => $iat, //issued at --> time which the JWT was issued
       "nbf" => $nbf, //not before --> time before the JWT must NOT be accepted for processing
       "data" => array(
           "id" => $user->id,
           "firstname" => $user->firstname,
           "lastname" => $user->lastname,
           "email" => $user->email
       )
    );
 
    // set response code
    http_response_code(200);
 
    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt
            )
        );
 
}
 
// login failed
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}
?>