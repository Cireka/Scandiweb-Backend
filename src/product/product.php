<?php
namespace src\product;
abstract class product
{
    protected string $id;
    protected string $name;
    protected float $price;

    protected function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = $data['price'];

    }

    protected abstract function saveProduct(\database $db);

    protected abstract function jsonSerialize();

}