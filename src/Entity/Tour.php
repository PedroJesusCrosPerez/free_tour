<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Route $route = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    private ?User $guide = null;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\OneToOne(mappedBy: 'tour', cascade: ['persist', 'remove'])]
    private ?Report $report = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getGuide(): ?User
    {
        return $this->guide;
    }

    public function setGuide(?User $guide): static
    {
        $this->guide = $guide;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTour($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTour() === $this) {
                $reservation->setTour(null);
            }
        }

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(Report $report): static
    {
        // set the owning side of the relation if necessary
        if ($report->getTour() !== $this) {
            $report->setTour($this);
        }

        $this->report = $report;

        return $this;
    }

    // My methods
    function getFormatDatetime(String $format): String
    {
        return $this->getDatetime()->format($format);
    }

    function getDatetimeFormated(): String
    {
        return $this->getDatetime()->format('d/m/Y H:i');
    }

    function getDatetimeFormatedForFullCalendar(): String
    {
        return $this->getDatetime()->format('Y-m-d H:i');
    }

    // DATE
    function getFormatedDate($format): String
    {
        return $this->getDatetime()->format($format);
    }

    function getFormatedDate_es(): String
    {
        return $this->getDatetime()->format('d/m/Y');
    }

    function getFormatedDate_en(): String
    {
        return $this->getDatetime()->format('Y-m-d');
    }

    function getDate(): String
    {
        return $this->getDatetime()->format('d/m/Y');
    }


    // TIME
    function getFormatedTime($format): String
    {
        return $this->getDatetime()->format($format);
    }
    function getTime(): String
    {
        return $this->getDatetime()->format('H:i');
    }
    
    // to string
    public function __toString()
    {
        $route = $this->getRoute();
        return $route->getName() . " | " . $this->getDatetime()->format('d/m/Y H:i');
    }
}
