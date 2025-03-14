<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['seance:read', 'seance:write'])]
    private ?int $id = null;

    #[Groups(['seance:read', 'seance:write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heure = null;

    #[Groups(['seance:read', 'seance:write'])]
    #[ORM\Column(length: 255)]
    private ?string $type_seance = null;

    #[Groups(['seance:read', 'seance:write'])]
    #[ORM\Column(length: 255)]
    private ?string $theme_seance = null;

    #[Groups(['seance:read', 'seance:write'])]
    #[ORM\Column(length: 255)]
    private ?string $niveau_seance = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coach $coach_id = null;

    #[Groups(['seance:read', 'seance:write'])]
    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    /**
     * @var Collection<int, Sportif>
     */
    #[ORM\ManyToMany(targetEntity: Sportif::class, mappedBy: 'seances')]
    private Collection $sportifs;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\ManyToMany(targetEntity: Exercice::class, inversedBy: 'seances')]
    private Collection $exercices;

    public function __construct()
    {
        $this->sportifs = new ArrayCollection();
        $this->exercices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->date_heure;
    }

    public function setDateHeure(\DateTimeInterface $date_heure): static
    {
        $this->date_heure = $date_heure;

        return $this;
    }

    public function getTypeSeance(): ?string
    {
        return $this->type_seance;
    }

    public function setTypeSeance(string $type_seance): static
    {
        $this->type_seance = $type_seance;

        return $this;
    }

    public function getThemeSeance(): ?string
    {
        return $this->theme_seance;
    }

    public function setThemeSeance(string $theme_seance): static
    {
        $this->theme_seance = $theme_seance;

        return $this;
    }

    public function getNiveauSeance(): ?string
    {
        return $this->niveau_seance;
    }

    public function setNiveauSeance(string $niveau_seance): static
    {
        $this->niveau_seance = $niveau_seance;

        return $this;
    }

    public function getCoachId(): ?Coach
    {
        return $this->coach_id;
    }

    public function setCoachId(?Coach $coach_id): static
    {
        $this->coach_id = $coach_id;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Sportif>
     */
    public function getSportifs(): Collection
    {
        return $this->sportifs;
    }

    public function addSportif(Sportif $sportif): static
    {
        if (!$this->sportifs->contains($sportif)) {
            $this->sportifs->add($sportif);
            $sportif->addSeance($this);
        }

        return $this;
    }

    public function removeSportif(Sportif $sportif): static
    {
        if ($this->sportifs->removeElement($sportif)) {
            $sportif->removeSeance($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        $this->exercices->removeElement($exercice);

        return $this;
    }
}
