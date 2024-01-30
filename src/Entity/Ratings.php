<?php

namespace App\Entity;

use App\Repository\RatingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingsRepository::class)]
class Ratings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $guide_rating = null;

    #[ORM\Column]
    private ?float $route_rating = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comments = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuideRating(): ?float
    {
        return $this->guide_rating;
    }

    public function setGuideRating(float $guide_rating): static
    {
        $this->guide_rating = $guide_rating;

        return $this;
    }

    public function getRouteRating(): ?float
    {
        return $this->route_rating;
    }

    public function setRouteRating(float $route_rating): static
    {
        $this->route_rating = $route_rating;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
