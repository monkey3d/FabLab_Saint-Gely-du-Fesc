<?php

namespace App\Entity;

use App\Entity\TrainingManagement\TrainingUser;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    const TITLES = [
        'Monsieur'  => 0,
        'Madame'  => 1
    ];
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uid = null; 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?Uuid
    {
        return $this->uid;
    }

    public function setUid(Uuid $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
    
    //-------------------------------------------------------- propriétés non mappées car gérées par le ldap ----------------------------------
    private ?string $cn = null;
    
    private ?string $sn = null;

    private ?string $mail = null;

    private ?string $givenName = null;

    private ?string $displayName = null;

    private ?string $homePhone = null;

    private ?string $mobile = null;

    private ?bool $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\Column]
    private ?int $loginCount = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TrainingUser::class)]
    private Collection $trainingUsers;
    
    public function __construct()
    {
        $this->loginCount = 0;
        $this->verified = false;
        $this->trainingUsers = new ArrayCollection();
    }

    public function getCn(): ?string
    {
        return $this->cn;
    }

    public function SetCn(string $cn): self
    {
        $this->cn = $cn;

        return $this;
    }

    public function getSn(): ?string
    {
        return $this->sn;
    }

    public function setSn(string $sn): self
    {
        $this->sn = $sn;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setGivenName(string $givenName): self
    {
        $this->givenName = $givenName;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): self
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function isTitle(): ?bool
    {
        return $this->title;
        //return \array_search($this->title, self::TITLES);
    }

    public function setTitle(bool $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLoginCount(): ?int
    {
        return $this->loginCount;
    }

    public function setLoginCount(int $loginCount): self
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return Collection<int, TrainingUser>
     */
    public function getTrainingUsers(): Collection
    {
        return $this->trainingUsers;
    }

    public function addTrainingUser(TrainingUser $trainingUser): self
    {
        if (!$this->trainingUsers->contains($trainingUser)) {
            $this->trainingUsers->add($trainingUser);
            $trainingUser->setUser($this);
        }

        return $this;
    }

    public function removeTrainingUser(TrainingUser $trainingUser): self
    {
        if ($this->trainingUsers->removeElement($trainingUser)) {
            // set the owning side to null (unless already changed)
            if ($trainingUser->getUser() === $this) {
                $trainingUser->setUser(null);
            }
        }

        return $this;
    }
    
}
