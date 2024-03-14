<?php

namespace src\product;
class Book extends Product
{
    private const string TYPE = "book";
    private float $weight;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->setWeight($data["weight"]);
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    // Public ??????
    public function saveProduct(\src\Database\DataBase $db): void
    {

        $sql = 'INSERT INTO products (name, SKU, price, product_type, weight) VALUES (:name, :SKU,:price, :product_type, :weight )';
        $stmt = $db->getPdo()->prepare($sql);
        $stmt->bindValue(':name', parent::getName());
        $stmt->bindValue(':price', parent::getPrice());
        $stmt->bindValue(':SKU', parent::getSku());
        $stmt->bindValue(':weight', $this->weight);
        $stmt->bindValue(':product_type', self::TYPE);
        if ($stmt->execute()) {
            $this->jsonSerialize();
        } else {
            http_response_code(500);
            echo 'Method Not Allowed';
        }
    }

    protected function jsonSerialize(): void
    {
        echo json_encode([
            'message' => 'Data saved successfully.',
            'name' => parent::getName(),
            'price' => parent::getPrice(),
            'SKU' => parent::getSku(),
            'weight' => $this->weight,
            'product_type' => self::TYPE
        ]);
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
