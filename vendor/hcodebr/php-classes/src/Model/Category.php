<?php

namespace Hcode\Model;

use Exception;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;
use \Hcode\Model\Product;

class Category extends Model {

    public static function listAll() {

        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");

    }

    public function save() {

        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
            ":idcategory"=>$this->getidcategory(),
            ":descategory"=>$this->getdescategory()
        ));

        $this->setData($results[0]);

        Category::UpdateFile();

    }

    public function get($idCategory){

        $sql = new Sql;

        $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", array(
            "idcategory"=>$idCategory
        ));

        $this->setData($results[0]);

    }

    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
            ":idcategory"=>$this->getidcategory()
        ]);

        Category::UpdateFile();

    }

    public static function UpdateFile(){

        $categories = Category::listAll();

        $html = [];

        foreach ($categories as $row) {
            
            array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
                       
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode("", $html));

    }

    public function getProducts($related = true){

        $sql = new Sql();

        if ($related === true) {

            return $sql->select("SELECT *
                FROM tb_products WHERE idproduct IN(
                    SELECT a.idproduct
                    FROM tb_products a
                    INNER JOIN tb_categoriesproducts b ON a.idproduct = b.idproduct
                    WHERE b.idcategory = :idcategory
                );", [
                    ":idcategory"=>$this->getidcategory()
            ]);

        }else{

            return $sql->select("SELECT *
                FROM tb_products WHERE idproduct NOT IN(
                    SELECT a.idproduct
                    FROM tb_products a
                    INNER JOIN tb_categoriesproducts b ON a.idproduct = b.idproduct
                    WHERE b.idcategory = :idcategory
                );", [
                    ":idcategory"=>$this->getidcategory()
            ]);

        }
    }

    public function addProduct(Product $product){

        $sql = new Sql();

        $sql->query("INSERT INTO tb_categoriesproducts (idcategory, idproduct) VALUES(:idcategory, :idproduct);", array(
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ));

    }

    public function removeProduct(Product $product){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_categoriesproducts WHERE idcategory = :idcategory AND idproduct = :idproduct;", array(
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ));

    }


}


?>