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
        if ($uri === "/postProduct") {
            $this->createProduct($type, $data);
        }


    }

    public function handleDelete($uri)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $path = explode('/', $uri);

        if ($path[1] === "deleteProducts") {
            $this->massDelete();
        } else if ($path[1] === "deleteProductsById") {
            $this->deleteById($data);
        }


    }

    private function deleteById($data)
    {
        $types = $data["type"];
        $SKUs = $data["SKU"];

        // Prepare the SQL statement outside the loop for efficiency
        $stmt = $this->connection->getPdo()->prepare("DELETE FROM :type WHERE SKU = :SKU");

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
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price, 'book' AS product_type, weight FROM books");
        $stmt->execute();
        $books = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Fetch furniture
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price, 'furniture' AS product_type, width_cm, height_cm, length_cm FROM furniture");
        $stmt->execute();
        $furniture = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Fetch DVDs
        $stmt = $this->connection->getPdo()->prepare("SELECT SKU, name, price, 'dvd' AS product_type, size_mb FROM dvds");
        $stmt->execute();
        $dvds = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Combine the results into a single array
        $products = array_merge($books, $furniture, $dvds);

// Encode the combined array as JSON
        echo json_encode($products);;

    }


}
