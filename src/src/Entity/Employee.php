<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $chief_id = null;

    #[ORM\Column(length: 63)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChiefId(): ?string
    {
        return $this->chief_id;
    }

    public function setChiefId(?string $chief_id): self
    {
        $this->chief_id = $chief_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
