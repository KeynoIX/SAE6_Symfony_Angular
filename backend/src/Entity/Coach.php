<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach extends Utilisateur
{
    #[Groups(['coach:read'])]
    #[ORM\Column(type: Types::JSON)]
    private array $specialites = [];

    #[Groups(['coach:read', 'coach:write'])]
    #[ORM\Column]
    private ?float $tarif_horaire = null;

    /**
     * @var Collection<int, Seance>
     */
    #[Groups(['coach:read'])]
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'coach_id')]
    private Collection $seances;

    /**
     * @var Collection<int, FicheDePaie>
     */
    #[ORM\OneToMany(targetEntity: FicheDePaie::class, mappedBy: 'coach_id')]
    private Collection $ficheDePaies;

    public function __construct()
    {
        $this->setRoles(['ROLE_COACH']);
        $this->seances = new ArrayCollection();
        $this->ficheDePaies = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }


    public function getSpecialites(): array
    {
        return $this->specialites;
    }

    public function setSpecialites(array $specialites): static
    {
        $this->specialites = $specialites;

        return $this;
    }

    public function getTarifHoraire(): ?float
    {
        return $this->tarif_horaire;
    }

    public function setTarifHoraire(float $tarif_horaire): static
    {
        $this->tarif_horaire = $tarif_horaire;

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
            $seance->setCoachId($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getCoachId() === $this) {
                $seance->setCoachId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheDePaie>
     */
    public function getFicheDePaies(): Collection
    {
        return $this->ficheDePaies;
    }

    public function addFicheDePaie(FicheDePaie $ficheDePaie): static
    {
        if (!$this->ficheDePaies->contains($ficheDePaie)) {
            $this->ficheDePaies->add($ficheDePaie);
            $ficheDePaie->setCoachId($this);
        }

        return $this;
    }

    public function removeFicheDePaie(FicheDePaie $ficheDePaie): static
    {
        if ($this->ficheDePaies->removeElement($ficheDePaie)) {
            // set the owning side to null (unless already changed)
            if ($ficheDePaie->getCoachId() === $this) {
                $ficheDePaie->setCoachId(null);
            }
        }

        return $this;
    }
}
