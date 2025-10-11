<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Puzzle>
     */
    #[ORM\OneToMany(targetEntity: Puzzle::class, mappedBy: 'theme')]
    private Collection $puzzles;

    /**
     * @var Collection<int, Statistic>
     */
    #[ORM\OneToMany(targetEntity: Statistic::class, mappedBy: 'theme')]
    private Collection $statistics;

    public function __construct()
    {
        $this->puzzles = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Puzzle>
     */
    public function getPuzzles(): Collection
    {
        return $this->puzzles;
    }

    public function addPuzzle(Puzzle $puzzle): static
    {
        if (!$this->puzzles->contains($puzzle)) {
            $this->puzzles->add($puzzle);
            $puzzle->setTheme($this);
        }

        return $this;
    }

    public function removePuzzle(Puzzle $puzzle): static
    {
        if ($this->puzzles->removeElement($puzzle)) {
            // set the owning side to null (unless already changed)
            if ($puzzle->getTheme() === $this) {
                $puzzle->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Statistic>
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistic $statistic): static
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
            $statistic->setTheme($this);
        }

        return $this;
    }

    public function removeStatistic(Statistic $statistic): static
    {
        if ($this->statistics->removeElement($statistic)) {
            // set the owning side to null (unless already changed)
            if ($statistic->getTheme() === $this) {
                $statistic->setTheme(null);
            }
        }

        return $this;
    }
}
