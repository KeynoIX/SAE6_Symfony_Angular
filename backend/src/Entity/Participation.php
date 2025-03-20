<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Seance::class, inversedBy: "participations")]
    #[ORM\JoinColumn(nullable: false)]
    private $seance;

    #[ORM\ManyToOne(targetEntity: Sportif::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $sportif;

    #[ORM\Column(type: 'string', length: 10)]
    private $presence = 'absent';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }
    public function setSeance(Seance $seance): self
    {
        $this->seance = $seance;
        return $this;
    }

    public function getSportif(): ?Sportif
    {
        return $this->sportif;
    }
    public function setSportif(Sportif $sportif): self
    {
        $this->sportif = $sportif;
        return $this;
    }

    public function getPresence(): ?string
    {
        return $this->presence;
    }
    public function setPresence(string $presence): self
    {
        $this->presence = $presence;
        return $this;
    }
}
