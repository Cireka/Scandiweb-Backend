<?php
namespace src\product;


class ProductController
{
    private  $database;

    function __construct( $database)
    {
        $this->database = $database;

    }

    public function handleGet($uri)
    {
      $path = explode('/',$uri);
      echo $path[1];

      if($path[1] === "products"){
          $this->getProducts();
      }
    }
    public function handlePost($uri)
    {
        echo $uri;
    }
    public function handleDelete($uri)
    {

    }


    private function getProducts()
    {
       $stmt = $this->database->getPdo()->prepare("SELECT * FROM products");
       $stmt->execute();
       $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        header('Content-Type: application/json');
        echo json_encode($products);

    }
    private function createProduct($data)
    {

    }
}
