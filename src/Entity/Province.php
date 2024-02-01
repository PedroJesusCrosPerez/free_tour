<?php

namespace App\Entity;

use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinceRepository::class)]
class Province
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: Locality::class, orphanRemoval: true)]
    private Collection $localities;

    public function __construct()
    {
        $this->localities = new ArrayCollection();
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
     * @return Collection<int, Locality>
     */
    public function getLocalities(): Collection
    {
        return $this->localities;
    }

    public function addLocality(Locality $locality): static
    {
        if (!$this->localities->contains($locality)) {
            $this->localities->add($locality);
            $locality->setProvince($this);
        }

        return $this;
    }

    public function removeLocality(Locality $locality): static
    {
        if ($this->localities->removeElement($locality)) {
            // set the owning side to null (unless already changed)
            if ($locality->getProvince() === $this) {
                $locality->setProvince(null);
            }
        }

        return $this;
    }

    
    public function __toString()
    {
        return $this->name;
    }
}
