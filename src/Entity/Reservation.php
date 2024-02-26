<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\Column(nullable: false)]
    private ?int $number_tickets = null;

    #[ORM\Column(nullable: true)]
    private ?int $assistants = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $tour = null;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: Ratings::class, orphanRemoval: true)]
    private Collection $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getNumberTickets(): ?int
    {
        return $this->number_tickets;
    }

    public function setNumberTickets(int $number_tickets): static
    {
        $this->number_tickets = $number_tickets;

        return $this;
    }

    public function getAssistants(): ?int
    {
        return $this->assistants;
    }

    public function setAssistants(?int $assistants): static
    {
        $this->assistants = $assistants;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }

    /**
     * @return Collection<int, Ratings>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Ratings $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setReservation($this);
        }

        return $this;
    }

    public function removeRating(Ratings $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getReservation() === $this) {
                $rating->setReservation(null);
            }
        }

        return $this;
    }


    // public function __toString(): string
    // {
    //     return $this->;
    // }

    // My methods
    public function getDate(): string
    {
        return $this->getDatetime()->format("d/m/Y");
    }
    public function getTime(): string
    {
        return $this->getDatetime()->format("H:i");
    }

}
