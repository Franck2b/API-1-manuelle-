<?php

namespace App\Entity;

use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['campaign:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['campaign:read', 'campaign:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['facebook', 'instagram', 'google', 'twitter', 'linkedin'])]
    private ?string $platform = null;

    #[ORM\Column(length: 255)]
    #[Groups(['campaign:read', 'campaign:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 50)]
    #[Groups(['campaign:read', 'campaign:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['draft', 'in_progress', 'archived'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['campaign:read', 'campaign:write'])]
    #[Assert\Range(min: 0, max: 100)]
    private ?int $progress = null;

    #[ORM\Column]
    #[Groups(['campaign:read'])]
    private ?\DateTimeImmutable $lastUpdated = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'campaigns')]
    #[ORM\JoinTable(name: 'campaign_collaborator')]
    #[Groups(['campaign:read', 'campaign:write'])]
    private Collection $collaborators;

    #[ORM\Column]
    #[Groups(['campaign:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->lastUpdated = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(?int $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTimeImmutable $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(User $collaborator): static
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    public function removeCollaborator(User $collaborator): static
    {
        $this->collaborators->removeElement($collaborator);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

