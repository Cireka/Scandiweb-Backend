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
    public function handleDelete($uri)
    {
        $path = explode('/', $uri);

        if($path[1] === "deleteProducts" && $path[2] === null){
            $this->massDelete();
        } else if(is_numeric($path[2])){
            $id = $path[2] ;
            $this->deleteById($id);
        }


    }

    private function deleteById($id)
    {

        $stmt = $this->connection->getPdo()->prepare("DELETE FROM products WHERE SKU = $id;");
        $stmt->execute();
    }
    private function massDelete()
    {
        $stmt = $this->connection->getPdo()->prepare("DELETE FROM products");
        $stmt->execute();
    }

    private function createProduct($type, $data)
    {
        $className = __NAMESPACE__ . '\\' . ucfirst($type);

        $item = new $className($data);
        $item->saveProduct($this->connection);

    }
    private function getProducts()
    {
        $stmt = $this->connection->getPdo()->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($products);

    }


}
