<?php

namespace App\Entity\TrainingManagement;

use App\Repository\TrainingManagement\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStartTime = null;

    #[ORM\Column]
    private ?int $numberAvailable = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prerequisite = null;

    #[ORM\Column(length: 255)]
    private ?string $former = null;

    #[ORM\Column]
    private ?float $cost = null;

    #[ORM\OneToMany(mappedBy: 'training', targetEntity: TrainingUser::class)]
    private Collection $trainingUsers;

    #[ORM\Column]
    private ?int $numberInitial = null;

    public function __construct()
    {
        $this->trainingUsers = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDateStartTime(): ?\DateTimeInterface
    {
        return $this->dateStartTime;
    }

    public function setDateStartTime(\DateTimeInterface $dateStartTime): self
    {
        $this->dateStartTime = $dateStartTime;

        return $this;
    }

    public function getNumberAvailable(): ?int
    {
        return $this->numberAvailable;
    }

    public function setNumberAvailable(int $numberAvailable): self
    {
        $this->numberAvailable = $numberAvailable;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrerequisite(): ?string
    {
        return $this->prerequisite;
    }

    public function setPrerequisite(?string $prerequisite): self
    {
        $this->prerequisite = $prerequisite;

        return $this;
    }

    public function getFormer(): ?string
    {
        return $this->former;
    }

    public function setFormer(string $former): self
    {
        $this->former = $former;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

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
            $trainingUser->setTraining($this);
        }

        return $this;
    }

    public function removeTrainingUser(TrainingUser $trainingUser): self
    {
        if ($this->trainingUsers->removeElement($trainingUser)) {
            // set the owning side to null (unless already changed)
            if ($trainingUser->getTraining() === $this) {
                $trainingUser->setTraining(null);
            }
        }

        return $this;
    }

    public function getNumberInitial(): ?int
    {
        return $this->numberInitial;
    }

    public function setNumberInitial(int $numberInitial): self
    {
        $this->numberInitial = $numberInitial;

        return $this;
    }
}
