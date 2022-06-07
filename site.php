<?php

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {

	$product = Product::listAll();
    
	$page = new Page();

	$page->setTpl("index", [
		"products"=>Product::chekList($product)
	]);

});


?>