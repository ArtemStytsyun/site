<?php
if($config->ajax) {
    //необходима проверка на стороне сервера
    if($input->post['productName']){
        $product_name = $pages->get('title='.$input->post['productName'])->product_name;
        $product_max_number = $pages->get('title='.$input->post['productName'])->number;
        $product_price = $pages->get('title='.$input->post['productName'])->price;
        $product_path = $pages->get('title='.$input->post['productName'])->path;
        //возможна проверка на разрешение продажи этому пользователю или любому другому, но пока ее нет 
        //проверка на верность данных
        if($product_price && $product_name && $product_max_number && $product_max_number > 0){
        
            //Находим продукт в корзине
            $product = $user->basket_product_list->get('product_url=' . $product_path);

            //Если запросили уже существующий в корзине товар
            if($product){
                //var_dump($product->number);
                //если запрос на ++
                if($input->post['flagInc'] == 'plus'){
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
                if($product->number > 0 && $input->post['flagInc'] == 'minus'){
                    $product->of(false);
                    $product->number--;
                    if($product->number == 0){
                        //предупреждение или ожидание и удаление продукта
                        $user->basket_product_list->remove($product);
                    }
                    else{
                        $product->save();
                    }
                    
                    $user->basket_product_list->save();
                    $user->save();
                    //var_dump($user->basket_product_list->first());
                }
            
                    
            }
            //если запрос на продукт, которого пока нет в корзине
            else if(!$product){
                //если запрос на ++
                if($input->post['flagInc'] == 'plus'){
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
        //сделать проверку на удаление
        //пересчитываем общую стоиость корзины
        $user->price = 0;
        if(count($user->basket_product_list) > 0){
            foreach($user->basket_product_list as $product1){
                // echo $product;
                $user->price += $pages->get($product1->product_url)->price * $product1->number; 
            }
        }
        
        $json = array (
            'product_name' => $product_name,
            'product_max_number' => $product_max_number,
            'product_price' => $product_price,
            'user_price' => $user->price,
            'product_number' => $user->basket_product_list->get('product_url=' . $product_path) == null? 0:$user->basket_product_list->get('product_url=' . $product_path)->number
        );
        echo json_encode ($json);
        return;
    }
}
?>
<?php 

if($input->post["pay"]){
    $success = false;
    //бекап
    $basket_product_list = clone $user->basket_product_list;
    $price = $user->price;

    //Посылаем запрос об оплате, если успех, то success = true
    //if(payquery = true)
    $success = true;

    if($success){
        //Очищаем корзину
        foreach($user->basket_product_list as $product){
            $product->delete();
            $user->basket_product_list->save();
        }
        $user->price = 0;
        $user->save();
    }
    $array = array(
        "success" => $success
    );
    echo json_encode($array);
    return;
    
}

?>
<?php include 'header.php' ?>
<?php

// $user->template->fields->add("basket_product_list");
// $user->template->fields->save();
// foreach ($user->template->fields as $f) {
//     echo $f->name . "<br>";
// }

// $new_product = $user->basket_product_list->getNew();
// $new_product->product_url = $pages->get("/products/a/")->path;
// $new_product->save();
// $user->basket_product_list->add($new_product);
// $user->save();     
//var_dump($user->basket_product_list->first()->product_url);
if($user->basket_product_list->count == 0) echo "Корзина пуста";
foreach($user->basket_product_list as $product) { 
    if($pages->get('path='.$product->product_url)->id == null){
        $product->delete();
        $user->basket_product_list->save();
        $user->save();
        continue;
    }

    $product_page = $pages->get($product->product_url);
    ?>
    <div class="card product m-1" style="width: 18rem;">
    <img src="<?php if($product_page->galery->count != 0) echo $product_page->galery->first->size(400,400)->url; ?>" class="card-img-top" alt="">
        <div class="card-body">
            <h5 class="card-title product__name"> <?=$product_page->title?></h5>
            <h5 class="card-title"> Цена: <?= $product_page->price?> </h5>
            <p class="card-text"> Описание товара<?= $product_page->product_description?> </p>
            <a href="<?=$product_page->url?>" class="btn btn-primary product__link">К товару</a>
            <button class="btn btn-primary minus">-</button>
            <span class="number"><?=$product->number?></span>
            <button class="btn btn-primary plus">+</button>
        </div>
    </div>
<?php } ?>
<div class="price"><?php echo $user->price?></div>
<button class="pay btn btn-primary plus">Купить</button>




<?php include "footer.php" ?>