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
$stmt = $product->categoryList();
$num = $stmt->rowCount();

if($num>0){
    $category_arr=array();
    $category_arr["records"]= array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $current_category = array(
            "categoryId" => $category_id,
            "categoryName" => $category_name
        );
        array_push($category_arr["records"], $current_category);
    }

    http_response_code(200);
    echo json_encode($category_arr);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}

?>