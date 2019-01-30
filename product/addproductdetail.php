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
    !empty($data->productId) &&
    !empty($data->productDetailName) &&
    !empty($data->colour) &&
    !empty($data->size)
){
 
    // set product property values
    $product->productId = $data->productId;
    $product->productDetailName = $data->productDetailName;
    $product->colour = $data->colour;
    $product->size = $data->size;
 
    // create the product
    if($product->addProductDetail()){
            $productDetailInfo = array(
                "productDetailId" => $product->productDetailId,
                "productId" => $product->productId,
                "productDetailName" => $product->productDetailName,
                "colour" => $product->colour,
                "size" => $product->size,
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($productDetailInfo);
    }

    else {
         $productDetailInfo = array(
                "productId" => $product->productId,
                "productDetailName" => $product->productDetailName,
                "colour" => $product->colour,
                "size" => $product->size,
                "message" => $product->message        
            );
        // set response code - 201 created
        http_response_code(400);
 
        // tell the user
        echo json_encode($productDetailInfo);
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