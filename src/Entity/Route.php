<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $coordinates = null;

    #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'routes')]
    private Collection $item;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime_end = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $programation = null;

    #[ORM\OneToMany(mappedBy: 'route', targetEntity: Tour::class, orphanRemoval: true)]
    private Collection $tours;

    //private $uploadsDir;
    public function __construct(/*ParameterBagInterface $parameterBag*/)
    {
        $this->item = new ArrayCollection();
        $this->tours = new ArrayCollection();
        //$this->uploadsDir = $parameterBag->get('routeImgDir');
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = /*$this->uploadsDir . */$photo;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(Item $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->item->removeElement($item);

        return $this;
    }

    public function getDatetimeStart(): ?\DateTimeInterface
    {
        return $this->datetime_start;
    }

    public function setDatetimeStart(\DateTimeInterface $datetime_start): static
    {
        $this->datetime_start = $datetime_start;

        return $this;
    }

    public function getDatetimeEnd(): ?\DateTimeInterface
    {
        return $this->datetime_end;
    }

    public function setDatetimeEnd(\DateTimeInterface $datetime_end): static
    {
        $this->datetime_end = $datetime_end;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getProgramation(): ?string
    {
        return $this->programation;
    }

    public function setProgramation(string $programation): static
    {
        $this->programation = $programation;

        return $this;
    }

    /**
     * @return Collection<int, Tour>
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): static
    {
        if (!$this->tours->contains($tour)) {
            $this->tours->add($tour);
            $tour->setRoute($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): static
    {
        if ($this->tours->removeElement($tour)) {
            // set the owning side to null (unless already changed)
            if ($tour->getRoute() === $this) {
                $tour->setRoute(null);
            }
        }

        return $this;
    }

    // My methods
    // public function getToursToString(): string
    // {
    //     return $this->getName();
    // }
    public function getMeetingPoint(): string
    {
        $coordinates = json_decode($this->getCoordinates());
        return "X: " . $coordinates->x . " Y: " . $coordinates->y;
    }

    // Average rating
        private ?float $average_rating = null;

        public function getAverageRating(): ?float
        {
            return $this->average_rating;
        }

        public function setAverageRating(?float $average_rating): static
        {
            $this->average_rating = $average_rating;

            return $this;
        }

    // Count rating
        private ?int $count_rating = null;

        public function getCountRating(): ?int
        {
            return $this->count_rating;
        }

        public function setCountRating(?int $count_rating): static
        {
            $this->count_rating = $count_rating;

            return $this;
        }

    // Items id
        /**
         * @return array<int>
         */
        public function getItemIds(): array
        {
            $itemIds = [];
            foreach ($this->item as $item) {
                $itemIds[] = $item->getId();
            }
            return $itemIds;
        }


    function getDatetimeStartFormated(): String
    { return $this->getDatetimeStart()->format('d/m/Y H:i'); }
    function getDatetimeEndFormated(): String
    { return $this->getDatetimeEnd()->format('d/m/Y H:i'); }


    public function __toString(): string
    {
        return $this->getName();
    }
}
