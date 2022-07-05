<?php

if($input->post["pay"]){
    $success = false;
    //бекап
    $basket_product_list = clone $user->basket_product_list;
    $price = clone $user->price;

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
    
}
