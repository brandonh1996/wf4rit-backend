<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// check email existence here// get posted data
//$data = file_get_contents("php://input");

$email = $_POST['email'];
$password = hash('sha256', $_POST['password']);


// set product property values
$user->email = $email;
$email_exists = $user->emailExists();
 
// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

use global; 

$salt = "ImCreatingThisSoItsALotHarderToGuess256";

var_dump($email_exists);

// check if email exists and if password is correct
if($email_exists && (0 == (strcmp($password, $user->password)))){
    echo "poop";
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