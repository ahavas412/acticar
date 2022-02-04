<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[UniqueEntity("licensePlate")]
class Car
{
    const NEW = "NEW"; // Neuf
    const USED = "USED"; // Occasion
    const BROKEN = "BROKEN"; // Cassé

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $state;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $licensePlate;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        if (in_array($state, self::getAllStates())) {
            $this->state = $state;

            return $this;
        } else {
            throw new InvalidArgumentException("The state $state is not a valid state, valid states are : " . implode(", ", $this->getStates()));
        }
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public static function getAllStates(): array
    {
        return [
            'Neuve' => self::NEW,
            'Occasion' => self::USED,
            'Cassé' => self::BROKEN,
        ];
    }
}
