<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
#[ORM\Table(name: '`ehub_subscriber`')]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subscribers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private bool $isSubscribed = false;

    #[ORM\Column]
    private bool $hasReceived = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    /**
     * @var Collection<int, NewsletterSubscriber>
     */
    #[ORM\OneToMany(targetEntity: NewsletterSubscriber::class, mappedBy: 'subscriber')]
    private Collection $newsletterSubscribers;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->newsletterSubscribers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isSubscribed(): bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    public function isHasReceived(): bool
    {
        return $this->hasReceived;
    }

    public function setHasReceived(bool $hasReceived): static
    {
        $this->hasReceived = $hasReceived;

        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, NewsletterSubscriber>
     */
    public function getNewsletterSubscribers(): Collection
    {
        return $this->newsletterSubscribers;
    }

    public function addNewsletterSubscriber(NewsletterSubscriber $newsletterSubscriber): static
    {
        if (!$this->newsletterSubscribers->contains($newsletterSubscriber)) {
            $this->newsletterSubscribers->add($newsletterSubscriber);
            $newsletterSubscriber->setSubscriber($this);
        }

        return $this;
    }

    public function removeNewsletterSubscriber(NewsletterSubscriber $newsletterSubscriber): static
    {
        if ($this->newsletterSubscribers->removeElement($newsletterSubscriber)) {
            // set the owning side to null (unless already changed)
            if ($newsletterSubscriber->getSubscriber() === $this) {
                $newsletterSubscriber->setSubscriber(null);
            }
        }

        return $this;
    }
}