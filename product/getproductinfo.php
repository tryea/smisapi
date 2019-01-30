<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/product.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 
// query products
$id = $_GET['id'];
$branchId = $_GET['branch'];

if($product->productDetailInfoPerId($id,$branchId)){
    $productDetailInfo = array(
                "productDetailId" => $product->productDetailId,
                "productDetailName" => $product->productDetailName,
                "productName" => $product->productName,
                "categoryName" => $product->categoryName,
                "colour" => $product->colour,
                "size" => $product->size,
                "stock" => $product->stock,
                "imagePath" => $product->pathImage
            );
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode($productDetailInfo);
}
else{
    http_response_code(404);
    echo json_encode(array(
        "message" => "No products found.",
        "branch" => $branchId
    ));
}

?>