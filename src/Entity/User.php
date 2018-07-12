<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $dateRegistered;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $roles;

    /**
     * Many Users have Many Favorites
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Ad")
     * @ORM\JoinTable(name="user_ads",
     *  joinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="ad_id", referencedColumnName="id")}
     *     )
     */
    private $annonces;

    /**
     * An user can create many Ads
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="user")
     */
    private $mesAnnonces;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateRegistered(): ?\DateTimeInterface
    {
        return $this->dateRegistered;
    }

    public function setDateRegistered(\DateTimeInterface $dateRegistered): self
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    public function getRoles(): array
    {
        return array('ROLE_USER');
    }

    public function getAds():array
    {
        return array(Ad::class);
    }

    public function addAds(Ad $ad) :self
    {
        $this->annonces[] =$ad;
        return $this;
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return ArrayCollection
     */
    public function getMesAnnonces(): ArrayCollection
    {
        return $this->mesAnnonces;
    }

   public function addMesAnnonces(Ad $ad) :self
   {
        $this->mesAnnonces[]=$ad;
        return $this;
   }
}
