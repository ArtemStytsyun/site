<?php 
if(!$user->roles->get('seller') && !$user->roles->get('superuser')) exit("Ты не продавец!!!");
if($input->post["addProduct"]){
   
    if(isset($input->post["productName"]) && isset($input->post["productPrice"]) && isset($input->post["productNumber"]) && isset($input->post["productDescription"]) && is_numeric($input->post["productNumber"]) && is_numeric($input->post["productPrice"])){
        $data = array(
        'productName' => strtolower(htmlspecialchars(filter_var(str_replace(" ",'_',trim($input->post['productName'])), FILTER_SANITIZE_STRING))),
        'productPrice' => (int)$input->post['productPrice'],
        'productNumber' => (int)$input->post['productNumber'],
        'productDescription' => htmlspecialchars(filter_var(str_replace(" ",'_',trim($input->post['productDescription'])), FILTER_SANITIZE_STRING))
        );
       
        $products = $pages->get("/Products/");
        echo $products->path . $data['productName'];
        if($pages->get("name=".$data['productName'])->id == null){
            $product = $pages->add("Product", "/Products/",$data['productName'], [
                'title' => $data['productName'],
                'product_name' => $data['productName'],
                'price' => $data['productPrice'],
                'product_description' => $data['productDescription'],
                'number' => $data['productNumber']
                
            ]);
            $product->save();
            
        }
        $products->save();
        header('Location: /processwire-dev/add_product');
    }

}


?>
<?php include "header.php"?>
<?php ?>
<form class="form-horizontal my-5 needs-validation" method='post' novalidate> 
    
        <div class="form-group py-2 has-validation">
            <label for="productName">Product Name</label>
            <input class="form-control" type="text" id="productName" name="productName" required>
            <div class="invalid-feedback">
                Введите название продукта.
            </div>
        </div>
        <div class="form-group py-2 has-validation">
            <label for="productPrice">Product Price</label>
            <input class="form-control" type="text" id="productPrice" name="productPrice" placeholder="0 руб."required>
            <div class="invalid-feedback">
                Неверный формат почты.
            </div>
        </div>
        <div class="form-group py-2 has-validation">
            <label for="productNumber">Product Number</label>
            <input class="form-control" type="text" id="productNumber" name="productNumber" required>
            <div class="invalid-feedback">
                Неверне количество.
            </div>
        </div>
        <div class="form-group py-2 has-validation">
            <label for="productDescription">Product Description</label>
            <input class="form-control" type="text" id="productDescription" name="productDescription" required>
            <div class="invalid-feedback">
                Неверне количество.
            </div>
        </div>
        <div class="py-2">
            <input class="btn btn-primary " type="submit" value="addProduct" name="addProduct" >
        </div>
    </form>   
<?php include "footer.php" ?>