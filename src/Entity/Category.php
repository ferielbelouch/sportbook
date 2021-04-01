<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $minAge;

    /**
     * @ORM\Column(type="float")
     */
    private $maxAge;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMinAge(): ?float
    {
        return $this->minAge;
    }

    public function setMinAge(float $minAge): self
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getMaxAge(): ?float
    {
        return $this->maxAge;
    }

    public function setMaxAge(float $maxAge): self
    {
        $this->maxAge = $maxAge;

        return $this;
    }
}
