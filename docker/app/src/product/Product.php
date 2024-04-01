<?php

namespace docker\app\src\product;
abstract class Product
{
    protected string $name;
    protected float $price;
    protected string $sku;

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    protected function __construct($data)
    {
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->sku = $data['sku'];
    }

    protected function checkSkuValidity($db):bool{

        $sku = $this->getSku();
        $tables = ['books', 'furniture', 'dvds'];

        foreach ($tables as $table) {
            $stmt = $db->getPdo()->prepare("SELECT COUNT(*) FROM $table WHERE SKU = :sku");
            $stmt->execute([':sku' => $sku]);
            if ($stmt->fetchColumn() > 0) {
                return false; // SKU already exists in the database
            }
        }

        return true; // SKU is valid
    }

    protected abstract function saveProduct(\docker\app\src\Database\DataBase $db);
    protected abstract function jsonSerialize();


}