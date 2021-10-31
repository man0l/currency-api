<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;

#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    attributes: [
        'pagination_enabled' => false        
    ],
)]
class Currency 
{   

    public function __construct(
        #[ApiProperty(identifier: true)]
        private ?string $id = null,
        private ?string $pair = null,
        private ?string $icon = null,
        private ?float $rate = null,
        private ?\DateTimeInterface $date = null        
    ) {
          $this->date = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getPair()
    {
        return $this->pair;
    }

    public function setPair($pair)
    {
        $this->pair = $pair;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon) 
    {
        $this->icon = $icon;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

}

