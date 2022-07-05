<?php include "header.php" ?>
<?php 
var_dump($pages->find('name=produdcts')->id);
echo $page->title;
echo $page->name;
echo $page->path;
echo $page->product_description;
echo $page->product_price;


?>
<?php include "footer.php" ?>