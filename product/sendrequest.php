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
    !empty($data->productDetailName) &&
    !empty($data->branchFrom) &&
    !empty($data->branchTo)
){
 
    // set product property values
    $product->productDetailName = $data->productDetailName;
    $product->branchFrom = $data->branchFrom ;
    $product->branchTo = $data->branchTo ;
 
    // Login
    if($product->sendRequest()){
            // create array
            $sendRequest_info = array(
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($sendRequest_info);
}
    else{
        $sendRequest_info = array(
            "message" => $product->message        
        );

        http_response_code(400);

        echo json_encode($sendRequest_info);
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