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
                    $product->delete();
                }
                $product->save();
                $user->basket_product_list->save();
                $user->save();
            }
         
                
        }
        //если запрос на продукт, которого пока нет в корзине
        if(!$product){
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
    //сделать проверку на удаление
    $json = array (
        'product_name' => $product_name,
        'product_max_number' => $product_max_number,
        'product_price' => $product_price,
        'product_number' => $user->basket_product_list->get("product_url=" . $product_path) == null? 0:$user->basket_product_list->get("product_url=" . $product_path)->number
    );
    echo json_encode ($json);
    return;
 }
?>