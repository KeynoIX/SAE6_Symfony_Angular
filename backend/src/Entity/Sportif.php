<?php

namespace App\Entity;

use App\Repository\SportifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SportifRepository::class)]
class Sportif extends Utilisateur
{
    #[Groups(['sportif:read', 'sportif:write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_inscription = null;

    #[Groups(['sportif:read', 'sportif:write'])]
    #[ORM\Column(length: 255)]
    private ?string $niveau_sportif = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\ManyToMany(targetEntity: Seance::class, inversedBy: 'sportifs')]
    private Collection $seances;

    public function __construct()
    {
        $this->setRoles(['ROLE_SPORTIF']);
        $this->seances = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }


    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): static
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getNiveauSportif(): ?string
    {
        return $this->niveau_sportif;
    }

    public function setNiveauSportif(string $niveau_sportif): static
    {
        $this->niveau_sportif = $niveau_sportif;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        $this->seances->removeElement($seance);

        return $this;
    }
}
