<?php

namespace src\product;
abstract class Product
{
    protected string $name;
    protected float $price;
    protected int $sku;

    public function getSku(): int
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    protected function __construct($data)
    {
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->sku = $data['sku'];
    }

    protected abstract function saveProduct(\src\Database\DataBase $db);



}