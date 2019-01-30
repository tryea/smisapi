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
    !empty($data->productName) &&
    !empty($data->categoryId)
){
 
    // set product property values
    $product->productName = $data->productName;
    $product->categoryId = $data->categoryId;
 
    // create the product
    if($product->addProduct()){
            $product_info = array(
                "productId"  => $product->productId,
                "productName" => $product->productName,
                "categoryId" => $product->categoryId,
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($product_info);
    }

    else {
       $product_info = array(
                "productName" => $product->productName,
                "categoryId" => $product->categoryId,
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(400);
 
        // tell the user
        echo json_encode($product_info);
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