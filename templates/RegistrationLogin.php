
<?php


unset($input->post);
if($input->post["registration"] && $input->post["name"] != "" && $input->post["password"] != "" && $input->post["email"] != ""){
    
    //Узнаем существует ли создаваемый пользователь
    $item = $users->get("name=" . $sanitizer->name($input->post["name"]).",email=".$sanitizer->email($input->post["email"]));

    //Если нет, то создаем его
    if($item instanceof ProcessWire\NullPage){
        $name = $sanitizer->name($input->post["name"]);
        $password = $input->post["password"];
        $email = $sanitizer->email($input->post["email"]);

        $users->add($name);
        $newUser = $users->get($name);
        $newUser->setOutputFormatting(false);
        $newUser->pass = $password;
        $newUser->email = $email;
        $newUser->addRole("siteuser");
        $newUser->save();
        
        if ($user->isLoggedin()){
            unset($input->post);
            unset($__POST);
            header('Location: '.'http://localhost/processwire-dev/userpage');
            //Перенаправляет на страницу пользователя
        }else{
        
        }

    }else{
       
    }  
 
    unset($input->post);
}

if($input->post["login"] && $input->post["login_name"] != "" && $input->post["login_password"] != ""){   
    
    if ($session->login($input->post["login_name"], $input->post["login_password"])){
        unset($input->post);
        header('Location: '.'http://localhost/processwire-dev/userpage');
        //Перенаправляет на страницу пользователя
    }else{
       
    }
}

?>

<?php include "./header.php";  


$client_id = 8203149; // ID приложения
$client_secret = '7YyZd6qSExlJItfWOu9W'; // Защищённый ключ
$redirect_uri = 'http://localhost/processwire-dev/RegistrationLogin/'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize'; // Ссылка для авторизации на стороне ВК

$params = [ 'client_id' => $client_id, 'redirect_uri'  => $redirect_uri, 'response_type' => 'code']; // Массив данных, который нужно передать для ВК содержит ИД приложения код, ссылку для редиректа и запрос code для дальнейшей авторизации токеном


if (isset($_GET['code'])) {
    $result = false;
    $params = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
        
    ];

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params = [
            'uids' => $token['user_id'],
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token'],
            'v' => '5.101'];

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['id'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
            var_dump($userInfo);
            //Непосредственно аунтификация
            // if($user->hasRole("siteuser")){
            //     $session->logout();
            //     // $session->start();
            //     if($users->find("id=".$userInfo['response'][0]['id'])){
                    
            //     }
            // }
            // if($result)
            // header('Location: /processwire-dev/');
            
        }
    }
    else{
        exit("error");
    }
    
}


echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';?>
    
    <!-- Registration form -->
    <form class="form-horizontal my-5 needs-validation" method='post' novalidate> 
        <div class="form-group py-2 has-validation">
            <label for="name">name</label>
            <input class="form-control" type="text" id="name" name="name" required>
            <div class="invalid-feedback">
                Введите имя.
            </div>
        </div>
        <div class="form-group py-2 has-validation">
            <label for="email">email</label>
            <input class="form-control" type="email" id="email" name="email" required>
            <div class="invalid-feedback">
                Неверный формат почты.
            </div>
        </div>
        <div class="form-group py-2 has-validation">
            <label for="password">password</label>
            <input class="form-control" type="password" id="password" name="password" required>
            <div class="invalid-feedback">
                Введите пароль.
            </div>
        </div>
        <div class="py-2">
            <input class="btn btn-primary " type="submit" value="registration" name="registration" >
        </div>
    </form>   

    <!-- Login form -->
    <form class="form-horizontal my-5 needs-validation" method='post' novalidate> 
        <div class="form-group py-2">
            <label for="login_name">name</label>
            <input class="form-control" type="text" id="login_name" name="login_name" required>
            <div class="invalid-feedback">
                Введите имя.
            </div>
        </div>
        <div class="form-group py-2">
            <label for="login_password">password</label>
            <input class="form-control" type="password" id="login_password" name="login_password" required>
            <div class="invalid-feedback">
                Введите пароль.
            </div>
        </div>
        <div class="py-2">
            <input class="btn btn-primary" type="submit" value="login" name="login" >
        </div>
    </form> 
    
<?php include "./footer.php"; ?>
