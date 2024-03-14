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
        $stmt->execute();


    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
