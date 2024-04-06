<?php

namespace src\product;


class ProductController
{

    protected \src\Database\DataBase $connection;

    function __construct($db)
    {
        $this->connection = $db;

    }

    public function handleGet($uri):void
    {
        $path = explode('/', $uri);
        if ($path[1] === "products") {
            $this->getProducts();
        }


    }

    public function handlePost($uri):void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $type = $data['type'];
        $path = explode('/', $uri);

        if ($path[1] === "postProduct") {
            $this->createProduct($type, $data);
        }


    }

    public function handleDelete($uri):void
    {
        if ($uri === "/deleteProducts") {
            $this->massDelete();
        }

    }
    public function handlePatch($uri):void
    {
        if ($uri === "/deleteProductsById") {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->deleteById($data);
        }

    }

    private function deleteById($data):void
    {
        $types = $data["type"];
        $SKUs = $data["SKU"];

        // Iterate over each type and SKU pair to execute the delete query
        foreach ($types as $index => $type) {
            $SKU = $SKUs[$index];

            // Manually concatenate the table name into the query
            $sql = "DELETE FROM $type WHERE SKU = :SKU";
            $stmt = $this->connection->getPdo()->prepare($sql);

            $stmt->bindParam(':SKU', $SKU);
            $stmt->execute();
        }
    }





    private function massDelete()
    {
        $stmt = $this->connection->getPdo()->prepare("DELETE FROM furniture");
        $stmt->execute();

        $stmt = $this->connection->getPdo()->prepare("DELETE FROM dvds");
        $stmt->execute();

        $stmt = $this->connection->getPdo()->prepare("DELETE FROM books");
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
        // Fetch books
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price, product_type, attribute, unit, attribute_value  FROM books");
        $stmt->execute();
        $books = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Fetch furniture
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price,  product_type, unit, attribute, attribute_value  FROM furniture");
        $stmt->execute();
        $furniture = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Fetch DVDs
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price,  product_type, attribute, unit, attribute_value  FROM dvds");
        $stmt->execute();
        $dvds = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Combine the results into a single array
        $products = array_merge($books, $furniture, $dvds);

// Encode the combined array as JSON
        echo json_encode($products);;

    }


}
