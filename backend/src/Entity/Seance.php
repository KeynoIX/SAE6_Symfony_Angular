<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Symfony\Component\Validator\Constraints as Assert;

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
     * #[Assert\Count(
     * max: 3,
     * maxMessage: "Une séance ne peut pas avoir plus de 3 sportifs."
     * )]
     */
    #[ORM\ManyToMany(targetEntity: Sportif::class, mappedBy: 'seances')]
    private Collection $sportifs;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: "seance", cascade: ["persist", "remove"])]
    private $participations;


    /**
     * @var Collection<int, Exercice>
     * @Assert\Count(
     *      min = 1,
     *      max = 6,
     *      minMessage = "Vous devez sélectionner au moins 1 exercice.",
     *      maxMessage = "Vous ne pouvez sélectionner que jusqu'à 6 exercices."
     * )
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
        if ($this->sportifs->count() >= 3) {
            throw new \Exception("Cette séance est complète.");
        }

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

    public function validateCoachAvailability(ExecutionContextInterface $context): void
    {
        $entityManager = $context->getObject()->getEntityManager();

        $existingSeance = $entityManager->getRepository(Seance::class)->findOneBy([
            'coach_id' => $this->coach_id,
            'date_heure' => $this->date_heure,
        ]);

        if ($existingSeance) {
            $context->buildViolation("Ce coach a déjà une séance prévue à cet horaire.")
                ->atPath('date_heure')
                ->addViolation();
        }
    }

    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setSeance($this);
        }
        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->removeElement($participation)) {
        }
        return $this;
    }
}
