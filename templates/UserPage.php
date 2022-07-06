<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <?php include "./header.php"?>

    <div class="container-sm col-md-6">
        <p>Name:<?=" " . $user->name?></p>
        <p>Email:<?=" " . $user->email?></p>
        <?php var_dump($user->template->fields);?>
        <p>Roles:<?php foreach($user->roles as $userRole) echo " " . $userRole->name?></p>
    </div>
    <?php if($user->hasRole('seller') || $user->hasRole('superuser')){ ?>
        <a href="http://localhost/processwire-dev/add_product" class="btn btn-primary">Добавить новый товар на сайт</a>
    <?php } ?>
    <?php include "./footer.php"?>
</body>
</html>