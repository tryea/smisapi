<?php
class Product{
 
    // database connection and table name
    private $conn;
    private $table_name = "products";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $categoryId;
    public $categoryName;
    public $created;
    public $username;
    public $password;
    public $userId;
    public $role;
    public $message;
    public $category;
    public $productId;
    public $productName;
    public $productDetailName;
    public $colour;
    public $size;
    public $stock;
    public $productDetailId;
    public $officeId;
    public $pathImage;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    function afterRegister($usernameinput){
        $check_username = $usernameinput;

        $query = "SELECT user_id, username,role FROM user_account WHERE username=:username" ;
        // prepare query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $check_username=htmlspecialchars(strip_tags($check_username));  
        // bind values
        $stmt->bindParam(":username", $check_username);

        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }

    }
    // Login
    function login(){
    
        // query to check login
        $query = "SELECT ua.user_id, ua.username, ua.role_id, ua.branch_id, r.role_name, b.branch_name
                FROM user_account ua, role r, branch b
                WHERE ua.username=:username AND ua.password=:password AND ua.role_id = r.role_id";
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));    
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
    
        // execute query
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            if($num>0){
                $this->message = "Login Success";
                $this->username = $row['username'];
                $this->userId = $row['user_id'];
                $this->role = $row['role_name'];
                $this->branch = $row['branch_name'];
                $this->branchId = $row['branch_id'];
                return true;
            }
            else{
                $this->message = "Login Failed, Password wrong or Username does not Exist";
                $this->username = $this->username;
                return false;
            }
        }
    
        return false;
        
    }




     // Register
    
     function register(){
    
        // query to check login
        //"SELECT user_id, username,role FROM user_account WHERE username=:username and password=:password ";
        $query = "SELECT * FROM user_account WHERE username=:username" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));  
        // bind values
        $stmt->bindParam(":username", $this->username);
    
        // execute query
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            if($num==0){
                $query = "INSERT INTO user_account(username,password,role_id,branch_id) VALUES (:username,:password,:role,:branch)";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->username=htmlspecialchars(strip_tags($this->username));
                $this->password=htmlspecialchars(strip_tags($this->password));
                $this->role=htmlspecialchars(strip_tags($this->role));
                $this->branch=htmlspecialchars(strip_tags($this->branch));


                // bind values
                $stmt->bindParam(":username", $this->username);
                $stmt->bindParam(":password", $this->password);
                $stmt->bindParam(":role", $this->role);
                $stmt->bindParam(":branch", $this->branch);
                // execute query
                if($stmt->execute()){
                    $this->message = "Register Success";
                    $this->username = $this->username;
                    $this->role = $this->role;
                    return true;


                    
                }
            }
            else{
                $this->message = "Register Failed,Username already Exist";
                $this->username = $row['username'];
                $this->userId = $row['user_id'];
                $this->role = $row['role'];
                return false;
            }
        }
    
        return false;
        
    }
    
    //add category
    function addCategory(){
    
        // query to check login
        //"SELECT user_id, username,role FROM user_account WHERE username=:username and password=:password ";
        $query = "SELECT * FROM category WHERE category_name=:categoryName" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->categoryName=htmlspecialchars(strip_tags($this->categoryName));  
        // bind values
        $stmt->bindParam(":categoryName", $this->categoryName);
    
        // execute query
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            if($num==0){
                $query = "INSERT INTO category(category_name) VALUES (:categoryName)";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->categoryName=htmlspecialchars(strip_tags($this->categoryName));

                // bind values
                $stmt->bindParam(":categoryName", $this->categoryName);
                // execute query
                if($stmt->execute()){
                    $query = "SELECT * FROM category WHERE category_name=:categoryName" ;

                    $stmt1 = $this->conn->prepare($query);
                    $this->categoryName=htmlspecialchars(strip_tags($this->categoryName));
                    $stmt1->bindParam(":categoryName", $this->categoryName);
                    if($stmt1->execute())
                    {
                        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                        $this->message = "Add Category Success";
                        $this->categoryName = $this->categoryName;
                        $this->categoryId = $row1['category_id'];
                        return true;
                    }
                    
                }
            }
            else{
                $this->message = "Add Category Failed,Category Name already Exist";
                $this->categoryName = $row['category_name'];
                return false;
            }
        }
    
        return false;
        
    }

    //add product
    function addProduct(){
    
        // query to check login
        //"SELECT user_id, username,role FROM user_account WHERE username=:username and password=:password ";
        $query = "SELECT * FROM product WHERE product_name=:productName AND category_id=:categoryId" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->productName=htmlspecialchars(strip_tags($this->productName));  
        $this->categoryId=htmlspecialchars(strip_tags($this->categoryId));
        // bind values
        $stmt->bindParam(":productName", $this->productName);
        $stmt->bindParam(":categoryId", $this->categoryId);
    
        // execute query
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            if($num==0){
                $query = "INSERT INTO product(product_name,category_id) VALUES (:productName,:categoryId)";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->productName=htmlspecialchars(strip_tags($this->productName));
                $this->categoryId=htmlspecialchars(strip_tags($this->categoryId));

                // bind values
                $stmt->bindParam(":productName", $this->productName);
                $stmt->bindParam(":categoryId", $this->categoryId);
                // execute query
                if($stmt->execute()){

                    $query = "SELECT * FROM product WHERE product_name=:productName AND category_id=:categoryId" ;

                    $stmt1 = $this->conn->prepare($query);
                    $this->productName=htmlspecialchars(strip_tags($this->productName));  
                    $this->categoryId=htmlspecialchars(strip_tags($this->categoryId));
                    // bind values
                    $stmt1->bindParam(":productName", $this->productName);
                    $stmt1->bindParam(":categoryId", $this->categoryId);
                    if($stmt1->execute())
                    {
                        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                        $this->message = "Add Product Success";
                        $this->productId = $row1['product_id'];
                        $this->productName = $this->productName;
                        $this->categoryId = $this->categoryId;
                        return true;
                    }
                }
            }
            else{
                $this->message = "Add Product Failed, Product Name already Exist";
                $this->productName = $row['product_name'];
                $this->categoryId = $row['category_id'];
                return false;
            }
        }
    
        return false;
        
    }

    //add product detail
    function addProductDetail(){
    //INSERT INTO product_detail(product_id, product_detail_name, colour, size, stock) VALUES (2, "abc", "def", "h", 2)
        $query = "SELECT * FROM product_detail WHERE product_detail_name=:productDetailName AND product_id=:productId" ;
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->productDetailName=htmlspecialchars(strip_tags($this->productDetailName));  
        $this->productId=htmlspecialchars(strip_tags($this->productId));
        // bind values
        $stmt->bindParam(":productDetailName", $this->productDetailName);
        $stmt->bindParam(":productId", $this->productId);
    
        // execute query
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();
            if($num==0){
                $query = "INSERT INTO product_detail(product_id, product_detail_name,color,size) VALUES (:productId,:productDetailName,:colour,:size)";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->productDetailName=htmlspecialchars(strip_tags($this->productDetailName));
                $this->productId=htmlspecialchars(strip_tags($this->productId));
                $this->colour=htmlspecialchars(strip_tags($this->colour));
                $this->size=htmlspecialchars(strip_tags($this->size));

                // bind values
                $stmt->bindParam(":productDetailName", $this->productDetailName);
                $stmt->bindParam(":productId", $this->productId);
                $stmt->bindParam(":colour", $this->colour);
                $stmt->bindParam(":size", $this->size);
                // execute query

                if($stmt->execute()){
                    $query = "SELECT * FROM product_detail WHERE product_id=:productId AND product_detail_name=:productDetailName AND color=:colour AND size=:size" ;
                    $stmt1 = $this->conn->prepare($query);
                    // sanitize
                    $this->productDetailName=htmlspecialchars(strip_tags($this->productDetailName));
                    $this->productId=htmlspecialchars(strip_tags($this->productId));
                    $this->colour=htmlspecialchars(strip_tags($this->colour));
                    $this->size=htmlspecialchars(strip_tags($this->size));

                    // bind values
                    $stmt1->bindParam(":productDetailName", $this->productDetailName);
                    $stmt1->bindParam(":productId", $this->productId);
                    $stmt1->bindParam(":colour", $this->colour);
                    $stmt1->bindParam(":size", $this->size);

                    if($stmt1->execute()){
                        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                        $this->message = "Add Product Detail Success";
                        $this->productDetailId = $row1['product_detail_id'];
                        $this->productId = $row1['product_id'];
                        $this->productDetailName = $row1['product_detail_name'];
                        $this->colour = $row1['colour'];
                        $this->size = $row1['size'];
                        return true;
                    }
                }
            }
                else{
                    
                    $this->productId = $this->productId;
                    $this->message = "Add Product Detail Failed, Product Detail Name in this category already exist";
                    $this->productDetailName = $this->productDetailName;
                    $this->colour = $this->colour;
                    $this->size = $this->size;
                    $this->stock = $this->stock;
                    return false;
                }
        }
        return false;
        
    }

    function roleList(){
        $query="SELECT * FROM role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function categoryList(){
        $query="SELECT * FROM category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function productList(){
        $query="SELECT * FROM product";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function branchList(){
        $query="SELECT * FROM branch";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function productDetailInfoPerId($id, $branchId) {
        $query = "SELECT pd.product_detail_id, pd.product_id, pd.product_detail_name, pd.color, pd.size, p.product_name, c.category_name 
                FROM product_detail pd, product p, category c
                WHERE pd.product_detail_id=:productDetailId AND p.product_id = pd.product_id AND c.category_id = p.category_id";
        $stmt = $this->conn->prepare($query);
        $this->productDetailId = $id;
        $this->branchId = $branchId;
        $this->productDetailId=htmlspecialchars(strip_tags($this->productDetailId));
        
        $stmt->bindParam(":productDetailId", $this->productDetailId);

        
        if($stmt->execute())
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->productDetailId = $row['product_detail_id'];
            $this->productDetailName = $row['product_detail_name'];
            $this->productId = $row['product_id'];
            $this->productName = $row['product_name'];
            $this->categoryName = $row['category_name'];
            $this->colour = $row['color'];
            $this->size = $row['size'];
            $path = "http://ersaptaaristo.xyz/uploads/" . "product_" . $this->productId . "_" . $this->productDetailId . "_" . $this->productDetailName . ".jpg";
            $this->pathImage = $path;
            $query1 = "SELECT * FROM stock WHERE product_detail_id=:productDetailId AND branch_id=:branchId ";
            $stmt1 = $this->conn->prepare($query1);
                    // sanitize
            $this->productDetailId=htmlspecialchars(strip_tags($this->productDetailId));
            $this->branchId=htmlspecialchars(strip_tags($this->branchId));

            $stmt1->bindParam(":productDetailId", $this->productDetailId);
            $stmt1->bindParam(":branchId", $this->branchId);

            if($stmt1->execute()){
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $num = $stmt->rowCount();
                if ($num == 0){
                    $this->stock = '0';
                }
                else{
                    $this->stock = $row1['quantity'];
                }

            }


            return true;
        }
        
    }


    function updateStock() {
        $query = "SELECT * FROM stock WHERE product_detail_id=:productDetailId AND branch_id=:branchId ";
        $stmt = $this->conn->prepare($query);
                    // sanitize
        $this->productDetailId=htmlspecialchars(strip_tags($this->productDetailId));
        $this->branchId=htmlspecialchars(strip_tags($this->branchId));

        $stmt->bindParam(":productDetailId", $this->productDetailId);
        $stmt->bindParam(":branchId", $this->branchId);

        if($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $num = $stmt->rowCount();
                if ($num == 0) {
                    $query1 = "INSERT INTO stock (product_detail_id, price, branch_id, quantity, storage_number)
                                VALUES (:productDetailId, :price, :branchId, :stock, :storageNumber)";
                    $stmt1 = $this->conn->prepare($query1);

                    $this->productDetailId=htmlspecialchars(strip_tags($this->productDetailId));
                    $this->price=htmlspecialchars(strip_tags($this->price));
                    $this->branchId=htmlspecialchars(strip_tags($this->branchId));
                    $this->stock=htmlspecialchars(strip_tags($this->stock));
                    $this->storageNumber=htmlspecialchars(strip_tags($this->storageNumber));
                    
                    $stmt1->bindParam(":productDetailId", $this->productDetailId);
                    $stmt1->bindParam(":price", $this->price);
                    $stmt1->bindParam(":branchId", $this->branchId);
                    $stmt1->bindParam(":stock", $this->stock);
                    $stmt1->bindParam(":storageNumber", $this->storageNumber);
                    if($stmt1->execute()){
                        $this->message = "Update Stock Success";
                        return true;
                    }
                    
                }

                else {

                    $currentStock = $row['quantity'];
                    $this->stock = $this->stock + $currentStock ;

                    $query = "UPDATE stock 
                            SET quantity=:stock, price=:price ,storage_number=:storageNumber
                            WHERE product_detail_id =:productDetailId AND branch_id =:branchId";
                    // prepare query
                    $stmt = $this->conn->prepare($query);
                    // sanitize
                    $this->stock=htmlspecialchars(strip_tags($this->stock));
                    $this->price=htmlspecialchars(strip_tags($this->price));
                    $this->storageNumber=htmlspecialchars(strip_tags($this->storageNumber));
                    $this->productDetailId=htmlspecialchars(strip_tags($this->productDetailId));
                    $this->branchId=htmlspecialchars(strip_tags($this->branchId));
                    // bind values
                    $stmt->bindParam(":stock", $this->stock);
                    $stmt->bindParam(":price", $this->price);
                    $stmt->bindParam(":storageNumber", $this->storageNumber);
                    $stmt->bindParam(":productDetailId", $this->productDetailId);
                    $stmt->bindParam(":branchId", $this->branchId);

                    // execute query
                    if($stmt->execute()){
                        $this->message = "Update Stock Success";
                        return true;
                    }
                }

            }
        else {
            $this->message = "abc";
            return false;
        }
    }
    
    function sendRequest() {
        $query = "INSERT INTO request_item(product_detail_name,branch_from,branch_to) VALUES (:productDetailName,:branchFrom,:branchTo)";
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->productDetailName=htmlspecialchars(strip_tags($this->productDetailName));
                $this->branchFrom=htmlspecialchars(strip_tags($this->branchFrom));
                $this->branchTo=htmlspecialchars(strip_tags($this->branchTo));

                // bind values
                $stmt->bindParam(":productDetailName", $this->productDetailName);
                $stmt->bindParam(":branchFrom", $this->branchFrom);
                $stmt->bindParam(":branchTo", $this->branchTo);

                if($stmt->execute()){
                    $this->message = "Request Success, Please Wait for Respond" ;
                    return true;
                }
    }

    function requestList($id){
        $query="SELECT *
                FROM request_item
                WHERE  branch_from =:branchId OR branch_to =:branchId ";
        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(":branchId", $id);

        $stmt->execute();
        return $stmt;
    }

    function getBranchName($id){
        $query="SELECT branch_name
                FROM branch
                WHERE  branch_id =:branchId ";
        $stmt = $this->conn->prepare($query);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(":branchId", $id);

        $stmt->execute();
        return $stmt;
    }
}