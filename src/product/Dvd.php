<?php

namespace src\product;


class Dvd extends Product
{
    private const  TYPE = "Dvd";
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

    public function saveProduct(\src\Database\DataBase $db): void
    {
        if(!parent::checkSkuValidity($db)){
            http_response_code(400);
            echo 'SKU already exists in the database.';
            return;
        }
        try{
            $sql = 'INSERT INTO dvds (name, SKU, price, attribute_value) VALUES (:name, :SKU,:price, :attribute_value)';
            $stmt = $db->getPdo()->prepare($sql);
            $stmt->bindValue(':name', parent::getName());
            $stmt->bindValue(':price', parent::getPrice());
            $stmt->bindValue(':SKU', parent::getSku());
            $stmt->bindValue(':attribute_value', $this->getSizeMb());
            if ($stmt->execute()) {
                $this->jsonSerialize();
            } else {
                http_response_code(400);
                echo 'Unsuccessful post request.';
            }
        }catch (\Exception $e){
            http_response_code(400);
            echo $e->getMessage();
        }

    }

    protected function jsonSerialize(): string
    {
        return json_encode([
            'message' => 'Data saved successfully.',
            'name' => parent::getName(),
            'price' => parent::getPrice(),
            'SKU' => parent::getSku(),
            'size' => $this->size_mb,
            'product_type' => self::TYPE
        ]);
    }
}