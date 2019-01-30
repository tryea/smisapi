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
    !empty($data->stock) &&
    !empty($data->productDetailId) &&
    !empty($data->branchId) &&
    !empty($data->storageNumber) &&
    !empty($data->price)
){
 
    // set product property values
    $product->stock = $data->stock;
    $product->productDetailId = $data->productDetailId;
    $product->branchId = $data->branchId;
    $product->storageNumber = $data->storageNumber;
    $product->price = $data->price;
    // create the product
    if($product->updateStock()){
            $updateStock_info = array(
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($updateStock_info);
    }

    else {
       $updateStock_info = array(
                "message" => $product->message,
                "branchId" => $product->branchId,
                "productDetailId" => $product->productDetailId        
            );
        // set response code - 201 created
        http_response_code(400);
 
        // tell the user
        echo json_encode($updateStock_info);
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