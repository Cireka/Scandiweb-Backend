<?php

namespace src\product;






class ProductController
{

    protected \src\Database\DataBase $connection;

    function __construct($db)
    {
        $this->connection = $db;

    }

    public function handleGet($uri)
    {
        $path = explode('/', $uri);
        if ($path[1] === "products") {
            $this->getProducts();
        }
    }

    public function handlePost($uri)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $type = $data['type'];
        if ($uri === "/postProduct"){
            $this->createProduct($type,$data);
        }


    }

    private function createProduct($type, $data)
    {
        $className = __NAMESPACE__ . '\\' . ucfirst($type);

        $item = new $className($data);
        $item->saveProduct($this->connection);

    }

    public function handleDelete($uri)
    {


    }


    private function getProducts()
    {
        $stmt = $this->database->getPdo()->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        echo json_encode($products);

    }


}
