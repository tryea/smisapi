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
$stmt = $product->requestList($id);
$num = $stmt->rowCount();

if($num>0){
    $request_arr=array();
    $request_arr["records"]= array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $branch_from_res = $product->getBranchName($branch_from);
        $branch_from_res_a = $branch_from_res->fetch(PDO::FETCH_ASSOC);
        $branch_from_name = $branch_from_res_a['branch_name'];

        $branch_to_res = $product->getBranchName($branch_to);
        $branch_to_res_a = $branch_to_res->fetch(PDO::FETCH_ASSOC);
        $branch_to_name = $branch_to_res_a['branch_name'];


        $current_request = array(
            "requestId" => $request_product_id,
            "productName" => $product_detail_name,
            "BranchFrom" => $branch_from_name,
            "BranchTo" => $branch_to_name
        );
        array_push($request_arr["records"], $current_request);
    }

    http_response_code(200);
    echo json_encode($request_arr);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}

?>