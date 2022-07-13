<?php 
    


    if(isset($input->cookie->name) && isset($input->cookie->pass)){
        $session->login($input->cookie->name, $input->cookie->pass);
        $session->redirect('/processwire-dev/');
    }
    // $user->template->fields->add("vk_id");
    // $user->template->fields->save();
    //Определяем под какой ролью сидит пользователь - если гость, даем возможность регстрации и входа, если другой, то предлагаем выйти из уетной записи
    $flagLogin = false; 
    foreach($user->roles as $userRole){ 
        if($userRole->name != "guest"){
            $flagLogin = true;
            break;
        }
    }

    //Функция выхода из профиля
    if($input->post["logout"]){
        $session->logout();
        $session->redirect('/processwire-dev/');
    }

    //Каждый раз считаем заново стоимость корзины
    
    // $user->template->fields->add("price");
    // $user->template->fields->save();
    $user->price = 0;
    foreach($user->basket_product_list as $product){
        $user->price += $pages->get($product->product_url)->price * $product->number; 
    }
    // $user->price->save();
    // $user->save();
    
?>                   


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/processwire-dev/site/templates/styles/main.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="d-flex flex-column">
    <p><?php if($user->price > 0) echo "В корзине: " . $user->price . " руб.";?></p>
    <!-- header -->
    <header class="sticky-top bg-primary">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary container-sm col-md-10">
            <a class="navbar-brand" href="http://localhost/processwire-dev/">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="container-fluid p-0">
                <div class="navbar-collapse collapse justify-content-between" id="navbarNavAltMarkup">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="/processwire-dev/Products/">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="/processwire-dev/Basket/">Basket</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Page3</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Page4</a></li>
                    </ul>
                    <div class="nav-item dropdown">
                        <a class="navbar-brand m-0 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"> <?php if($flagLogin){echo $user->name;} else {echo "Profile";} ?></a>                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            
                            <?php if($flagLogin){ ?>
                            <a class="dropdown-item" href="http://localhost/processwire-dev/userpage/">Your profile</a>
                            <form method='post'>
                                <input class="dropdown-item btn" type="submit" name="logout" value="Logout">
                            </form>
                            <?php }else{ ?>
                                
                            <a class="dropdown-item" href="http://localhost/processwire-dev/RegistrationLogin/">Login</a>
                            <a class="dropdown-item" href="http://localhost/processwire-dev/RegistrationLogin/">Register</a>
                            
                            <?php } ?>
                            
                        </div>
                    </div>                
                </div>  
            </div>
        </nav>
    </header>
    <!-- main -->
    <main class="container-sm col-md-10 mb-auto" >