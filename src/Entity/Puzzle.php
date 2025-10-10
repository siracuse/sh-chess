<?php

namespace App\Entity;

use App\Repository\PuzzleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PuzzleRepository::class)]
class Puzzle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $fen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    #[ORM\Column]
    private array $solution = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFen(): ?string
    {
        return $this->fen;
    }

    public function setFen(string $fen): static
    {
        $this->fen = $fen;

        return $this;
    }


    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSolution(): array
    {
        return $this->solution;
    }

    public function setSolution(array $solution): static
    {
        $this->solution = $solution;

        return $this;
    }
}
