<?php

namespace src\product;

class Furniture extends Product
{
    private const  TYPE = "Furniture";
    private string $Dimensions;

    public function getDimensions(): string
    {
        return $this->Dimensions;
    }

    public function setDimensions(string $data): void
    {
        $this->Dimensions = $data;
    }

    public function __construct($data)
    {
        parent::__construct($data);
        $this->setDimensions($data['dimensions']);
    }


    public function saveProduct(\src\Database\DataBase $db): void
    {
        if (!parent::checkSkuValidity($db)) {
            http_response_code(400);
            echo 'SKU already exists in the database.';
            return;
        }
        try {
            $sql = 'INSERT INTO furniture (name, SKU, price, attribute_value) VALUES (:name, :SKU,:price, :attribute_value)';
            $stmt = $db->getPdo()->prepare($sql);
            $stmt->bindValue(':name', parent::getName());
            $stmt->bindValue(':price', parent::getPrice());
            $stmt->bindValue(':SKU', parent::getSku());
            $stmt->bindValue(':attribute_value', $this->getDimensions());
            if ($stmt->execute()) {
                $this->jsonSerialize();
            } else {
                http_response_code(400);
                echo 'Unsuccessful post request.';
            }
        } catch (\Exception $e) {
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
            'width' => $this->getDimensions(),
            'product_type' => self::TYPE
        ]);
    }
}