<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use Hcode\Model\Product;

$app->get("/admin/categories", function(){

	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();

	$page->setTpl("categories", [
	"categories"=>$categories
	]);
	
});

$app->get("/admin/categories/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
	
});

$app->post("/admin/categories/create", function(){

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;

});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$ctg = new Category();

	$ctg->get((int)$idcategory);

	$ctg->delete();

	header("Location: /admin/categories");
	exit;

});

$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
	]);

});

$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);
	//idcategory já vem na rota do formulario enviado via post e o segundo e último parâmetro (descategory), vem no campo preenchido do formulário. Por isso o $_POST leva todas os parâmetros necessário para realizar um setter e depois salvar.
	
	$category->save();

	header("Location: /admin/categories");
	exit;

});

$app->get("/admin/categories/:idcategory/products", function($idcategory){

	User::verifyLogin();

	$categories = new Category();

	$categories->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products", array(
		"category"=>$categories->getValues(),
		"productsRelated"=>$categories->getProducts(true),
		"productsNotRelated"=>$categories->getProducts(false)
	));

});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

	$categories = new Category();

	$categories->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$categories->addProduct($product);

	header("Location: /admin/categories/".$idcategory."/products");
	exit;

});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

	$categories = new Category();

	$categories->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$categories->removeProduct($product);

	header("location: /admin/categories/".$idcategory."/products");
	exit;

});


?>