<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/product.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->username) &&
    !empty($data->password) &&
    !empty($data->role) &&
    !empty($data->branch)
){
 
    // set product property values
    $product->username = $data->username;
    $product->password = hash('sha256',$data->password);
    $product->role = $data->role;
    $product->branch = $data->branch;
    // create the product
    if($product->register()){
            $register_info = array(
                "username" => $product->username,
                "role" => $product->role,
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($register_info);
    }

    else {
        $register_info = array(
                    "userId" =>  $product->userId,
                    "username" => $product->username,
                    "role" => $product->role,
                    "message" => $product->message        
                );
        http_response_code(400);

        echo json_encode($register_info);
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Wrong format data"));
}

?>