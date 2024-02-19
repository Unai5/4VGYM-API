<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActivityType $ActivityType = null;

    #[ORM\ManyToMany(targetEntity: Monitor::class, inversedBy: 'activities')]
    private Collection $Monitors;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    public function __construct()
    {
        $this->Monitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityType(): ?ActivityType
    {
        return $this->ActivityType;
    }

    public function setActivityType(?ActivityType $ActivityType): static
    {
        $this->ActivityType = $ActivityType;

        return $this;
    }

    /**
     * @return Collection<int, Monitor>
     */
    public function getMonitors(): Collection
    {
        return $this->Monitors;
    }

    public function addMonitor(Monitor $monitor): static
    {
        if (!$this->Monitors->contains($monitor)) {
            $this->Monitors->add($monitor);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): static
    {
        $this->Monitors->removeElement($monitor);

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function toArray(): array
    {
        $monitorArray = [];
    
        foreach ($this->Monitors as $monitor) {
            $monitorArray[] = $monitor->toArray();
        }
    
        return [
            'id' => $this->id,
            'activity_type' => $this->ActivityType ? $this->ActivityType->toArray() : null,
            'monitors' => $monitorArray,
            'date_start' => $this->date_start ? $this->date_start->format('Y-m-d H:i:s') : null,
            'date_end' => $this->date_end ? $this->date_end->format('Y-m-d H:i:s') : null,
        ];
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }   

}
