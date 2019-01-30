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
$stmt = $product->roleList();
$num = $stmt->rowCount();

if($num>0){
    $role_arr=array();
    $role_arr["records"]= array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $current_role = array(
            "roleId" => $role_id,
            "roleName" => $role_name
        );
        array_push($role_arr["records"], $current_role);
    }

    http_response_code(200);
    echo json_encode($role_arr);
}
else{
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}

?>