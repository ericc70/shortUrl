<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
class Url
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $longUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $domain = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'urls')]
    private ?User $userId = null;

    #[ORM\OneToMany(mappedBy: 'url', targetEntity: UrlStatistic::class)]
    private Collection $urlStatistics;

    public function __construct()
    {
        $this->urlStatistics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLongUrl(): ?string
    {
        return $this->longUrl;
    }

    public function setLongUrl(string $longUrl): self
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, UrlStatistic>
     */
    public function getUrlStatistics(): Collection
    {
        return $this->urlStatistics;
    }

    public function addUrlStatistic(UrlStatistic $urlStatistic): self
    {
        if (!$this->urlStatistics->contains($urlStatistic)) {
            $this->urlStatistics->add($urlStatistic);
            $urlStatistic->setUrl($this);
        }

        return $this;
    }

    public function removeUrlStatistic(UrlStatistic $urlStatistic): self
    {
        if ($this->urlStatistics->removeElement($urlStatistic)) {
            // set the owning side to null (unless already changed)
            if ($urlStatistic->getUrl() === $this) {
                $urlStatistic->setUrl(null);
            }
        }

        return $this;
    }

    public function getAllClicks(): int
    {

        $clicks = 0;
        foreach ($this->urlStatistics as $statistic)
        {
            $clicks += $statistic->getClicks();
        }
        return $clicks;
    }
}
