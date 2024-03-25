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


    public function saveProduct(\src\Database\DataBase $db): void
    {
        if(!parent::checkSkuValidity($db)){
            http_response_code(400);
            echo 'SKU already exists in the database.';
            return;
        }
        try{

            $sql = 'INSERT INTO books (name, SKU, price, attribute_value) VALUES (:name, :SKU,:price, :weight)';
            $stmt = $db->getPdo()->prepare($sql);
            $stmt->bindValue(':name', parent::getName());
            $stmt->bindValue(':price', parent::getPrice());
            $stmt->bindValue(':SKU', parent::getSku());
            $stmt->bindValue(':weight', $this->getWeight());
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
            'weight' => $this->weight,
            'product_type' => self::TYPE
        ]);
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
