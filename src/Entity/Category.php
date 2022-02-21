<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Meals', mappedBy: 'category')]
    private $meal;

    public function __construct()
    {
        $this->meal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Meals>
     */
    public function getMeal(): Collection
    {
        return $this->meal;
    }

    public function addMeal(Meals $meal): self
    {
        if (!$this->meal->contains($meal)) {
            $this->meal[] = $meal;
            $meal->setCategory($this);
        }

        return $this;
    }

    public function removeMeal(Meals $meal): self
    {
        if ($this->meal->removeElement($meal)) {
            // set the owning side to null (unless already changed)
            if ($meal->getCategory() === $this) {
                $meal->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString() // Für z.B. ein Dropdownmenü, um die Kategorien als Text dort anzuzeigen
    {
        return $this->name;
    }
}
