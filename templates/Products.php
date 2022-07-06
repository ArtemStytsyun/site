<?php
   if($config->ajax) {
    //необходима проверка на стороне сервера
    $product_name = $pages->get('title='.$input->post["productName"])->product_name;
    $product_max_number = $pages->get('title='.$input->post["productName"])->number;
    $product_price = $pages->get('title='.$input->post["productName"])->price;
    $product_path = $pages->get('title='.$input->post["productName"])->path;
    //возможна проверка на разрешение продажи этому пользователю или любому другому, но пока ее нет 
    //проверка на верность данных
    if($product_price && $product_name && $product_max_number && $product_max_number > 0){
       
        //Находим продукт в корзине
        $product = $user->basket_product_list->get("product_url=" . $product_path);

        //Если запросили уже существующий в корзине товар
        if($product){
            //var_dump($product->number);
            //если запрос на ++
            if($input->post["flagInc"] == 'plus'){
                $product->of(false);
                if(empty($product->number)){
                    $product->number = 1;
                }
                else if($product->number < $product_max_number){
                    $product->number++;
                }
                $product->save();
                $user->basket_product_list->save();
                $user->save();
            }
            //если запрос на --
            if($product->number > 0 && $input->post["flagInc"] == 'minus'){
                $product->of(false);
                $product->number--;
                if($product->number == 0){
                    //предупреждение или ожидание и удаление продукта
                    $user->basket_product_list->remove($product);
                }
                $product->save();
                $user->basket_product_list->save();
                $user->save();
            }
         
                
        }
        //если запрос на продукт, которого пока нет в корзине
        else if(!$product){
            //если запрос на ++
            if($input->post["flagInc"] == 'plus'){
                $new_product = $user->basket_product_list->getNew();
                $new_product->product_url = $product_path;
                $new_product->number = 1;
                $new_product->save();
                $user->basket_product_list->add($new_product);
            }
            //если запрос на --
            
        }
        
  
        // $new_product = $user->basket_product_list->getNew();
        // $new_product->product_url = $pages->get("/products/a/")->path;
        // $new_product->save();
        // $user->basket_product_list->add($new_product);
    }
    $user->price = 0;
    if(count($user->basket_product_list) > 0){
        foreach($user->basket_product_list as $product1){
            // echo $product;
            $user->price += $pages->get($product1->product_url)->price * $product1->number; 
        }
    }
    //сделать проверку на удаление нужно
    $json = array (
        'product_name' => $product_name,
        'product_max_number' => $product_max_number,
        'product_price' => $product_price,
        'product_number' => $user->basket_product_list->get("product_url=" . $product_path) == null? 0:$user->basket_product_list->get("product_url=" . $product_path)->number
    );
    echo json_encode ($json);
    return;
 }
 $isSaller = false;
 if($user->hasRole('seller') || $user->hasRole('superuser')){
    $isSaller = true;
 }
?>

<?php include "header.php" ?>

<div class="d-flex flex-wrap my-1">
<?php foreach($page->children as $child){?>
    <div class="card product m-1" style="width: 18rem;">
        <img src="<?php if($child->galery->first != null) echo $child->galery->first->size(400,400)->url; ?>" class="card-img-top" alt="">
        <div class="card-body">
            <h5 class="card-title product__name"> <?=$child->title?></h5>
            <h5 class="card-title"> Цена: <?= $child->price?> </h5>
            <p class="card-text"> Описание товара<?= $child->product_description?> </p>
            <a href="<?=$child->url?>" class="btn btn-primary product__link">К товару</a>
            <button class="btn btn-primary minus">-</button>
            <span class="number"></span>
            <button class="btn btn-primary plus">+</button>
            <?php if($isSaller) { ?>
                <button class="btn btn-primary delete">Удалить</button>
                <button class="btn btn-primary edit">Редактировать</button>
            <?php } ?>
             
        </div>
    </div>
<?php } ?>
</div>

<?php include "footer.php" ?>