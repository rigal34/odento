<?php

namespace App\Entity;

use App\Repository\CateElectroniqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CateElectroniqueRepository::class)]
class CateElectronique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $electronique = null;
    // ✅ Méthode __toString juste après les propriétés (et avant les getters/setters)
    public function __toString(): string
{
    return $this->electronique ?? '...';
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElectronique(): ?string
    {
        return $this->electronique;
    }

    public function setElectronique(string $electronique): static
    {
        $this->electronique = $electronique;

        return $this;
    }
}
