<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $libelle;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="category")
     */
    private $ads;

    public function getId()
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAds(): array
    {

        return $this->Ads;
    }

    public function addAd(Ad $ad): self
    {
       $this->ads[]= $ad;
       return $this;
    }

    public function removeAd(Ad $ad)
    {
        $this->ads->removeElement($ad);
    }
}
