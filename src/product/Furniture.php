<?php

namespace src\product;

class Furniture extends Product
{
    private const string TYPE = "Furniture";
    private float $width_cm;
    private float $height_cm;
    private float $length_cm;

    public function getWidthCm(): float
    {
        return $this->width_cm;
    }

    public function getHeightCm(): float
    {
        return $this->height_cm;
    }

    public function getLengthCm(): float
    {
        return $this->length_cm;
    }


    public function __construct($data)
    {
        parent::__construct($data);
        $this->setWidth($data["width_cm"]);
        $this->setLength($data["length_cm"]);
        $this->setHeight($data["height_cm"]);
    }

    public function setWidth(float $size): void
    {
        $this->width_cm = $size;
    }

    public function setLength(float $size): void
    {
        $this->length_cm = $size;
    }

    public function setHeight(float $size): void
    {
        $this->height_cm = $size;
    }

    public function saveProduct(\src\Database\DataBase $db): void
    {
        if (!parent::checkSkuValidity($db)) {
            http_response_code(400);
            echo 'SKU already exists in the database.';
            return;
        }
        try {
            $sql = 'INSERT INTO furniture (name, SKU, price, product_type, height_cm, width_cm,length_cm) VALUES (:name, :SKU,:price, :product_type, :height,:width,:length )';
            $stmt = $db->getPdo()->prepare($sql);
            $stmt->bindValue(':name', parent::getName());
            $stmt->bindValue(':price', parent::getPrice());
            $stmt->bindValue(':SKU', parent::getSku());
            $stmt->bindValue(':height', $this->height_cm);
            $stmt->bindValue(':width', $this->width_cm);
            $stmt->bindValue(':length', $this->length_cm);
            $stmt->bindValue(':product_type', self::TYPE);
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
            'width' => $this->width_cm,
            'height' => $this->height_cm,
            'length' => $this->length_cm,
            'product_type' => self::TYPE
        ]);
    }
}