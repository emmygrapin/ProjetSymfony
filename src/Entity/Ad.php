<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *     message="le champs ne doit pas être vide"
     * )
     * @Assert\Length(
     *     max="60",
     *     maxMessage="60 caractères svp"
     * )
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @Assert\NotBlank(
     *     message="le champs ne doit pas être vide"
     * )
     * @Assert\Length(
     *     max="600",
     *     maxMessage="600 caractères maximum"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @Assert\NotBlank(
     *     message="le champs ne doit pas être vide"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $city;

    /**
     * @Assert\NotBlank(
     *     message="le champs ne doit pas être vide"
     * )
     * @Assert\Length(
     *     min="5",
     *     max="5",
     *     exactMessage="Ce champs doit contenir exactement 5 caractères"
     * )
     * @ORM\Column(type="string", length=5)
     */
    private $zip;

    /**
     * @Assert\NotBlank(
     *     message="le champs ne doit pas être vide"
     * )
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="ads")
     */
    private $category;

    /**
     * An ad is created by an user
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mesAnnonces")
     */
    private $user;

    /**
     * Many Ad have Many Users who like them: There are userLikers
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     * @ORM\JoinTable(name="ads_likers",
     *  joinColumns={@ORM\JoinColumn(name="ad_id",referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *     )
     */
    private $userLikers;

    /**
     * @var arrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Characteristic", mappedBy="annonce", cascade={"persist"}, orphanRemoval=true)
     */
    private $characteristics;

    public function __construct()
    {
        $this->userLikers = new ArrayCollection();
        $this->characteristics = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category) :self
    {
        $this->category=$category;
        return $this;
    }

    public function getUserLikers(){
        return $this->userLikers;
    }

    public function addUserLikers(User $userLiker) :self
    {
        if(!$this->userLikers->contains($userLiker)){
           $this->userLikers->add($userLiker);
        }
        return $this;
    }

    public function removeUserLiker(User $userLiker):self
    {
        if($this->userLikers->contains($userLiker)){
            $this->userLikers->removeElement($userLiker);
        }
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user) :self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCharacteristics(): Collection
    {
        return $this->characteristics;
    }

    public function addCharacteristic(Characteristic $characteristic):self
    {
        dump($characteristic);
        if(!$this->characteristics->contains($characteristic)){
            $characteristic->setAnnonce($this);
            $this->characteristics->add($characteristic);
            dump($characteristic);
        }
        return $this;
    }

    public function removeCharacteristic(Characteristic $characteristic):self
    {
        if($this->characteristics->contains($characteristic)){
            $this->characteristics->removeElement($characteristic);

            if($this === $characteristic->getAnnonce()){
                $characteristic->setAnnonce(null);
            }
        }
        return $this;
    }
}
