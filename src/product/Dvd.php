<?php

namespace src\product;


class Dvd extends Product
{
    private const string TYPE = "Dvd";
    private float $size_mb;

    public function getSizeMb(): float
    {
        return $this->size_mb;
    }

    public function __construct($data)
    {
        parent::__construct($data);
        $this->setSize($data["size_mb"]);
    }

    public function setSize(float $size): void
    {
        $this->size_mb = $size;
    }

    public function saveProduct( \src\Database\DataBase $db): void
    {
        $sql = 'INSERT INTO products (name, SKU, price, product_type, size_mb) VALUES (:name, :SKU,:price, :product_type, :size_mb )';
        $stmt = $db->getPdo()->prepare($sql);
        $stmt->bindValue(':name', parent::getName());
        $stmt->bindValue(':price', parent::getPrice());
        $stmt->bindValue(':SKU', parent::getSku());
        $stmt->bindValue(':size_mb', $this->size_mb);
        $stmt->bindValue(':product_type', self::TYPE);
        $stmt->execute();

    }
}