<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;

$app->get('/', function() {

	$product = Product::listAll();
    
	$page = new Page();

	$page->setTpl("index", [
		"products"=>Product::chekList($product)
	]);

});

$app->get("/categories/:idcategory", function($idcategory){

	$categories = new Category();

	$categories->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$categories->getValues(),
		"products"=>Product::chekList($categories->getProducts(true)),
	));

});




?>