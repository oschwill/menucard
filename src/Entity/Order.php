<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tisch;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $bnummer;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTisch(): ?string
    {
        return $this->tisch;
    }

    public function setTisch(?string $tisch): self
    {
        $this->tisch = $tisch;

        return $this;
    }

    public function getBnummer(): ?string
    {
        return $this->bnummer;
    }

    public function setBnummer(?string $bnummer): self
    {
        $this->bnummer = $bnummer;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
