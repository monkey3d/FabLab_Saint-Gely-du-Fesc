<?php

namespace App\Entity\SiteManagement;

use App\Repository\SiteManagement\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $openingStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isOpeningStatus(): ?bool
    {
        return $this->openingStatus;
    }

    public function setOpeningStatus(bool $openingStatus): self
    {
        $this->openingStatus = $openingStatus;

        return $this;
    }
}
