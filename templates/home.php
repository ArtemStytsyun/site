<?php namespace ProcessWire;

?>
<?php include "./header.php"; echo $user->name;?>
<div id="content">
	Homepage content 
	<?php var_dump($user->basket_product_list->count);?>

</div>	
<?php include "./footer.php"; ?>
