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
#[ORM\Table(name: '`ehub_newsletter`')]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $content;

    #[ORM\Column]
    private bool $canBePublished = false;

    #[ORM\Column]
    private bool $isSent = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    /**
     * @var Collection<int, NewsletterSubscriber>
     */
    #[ORM\OneToMany(targetEntity: NewsletterSubscriber::class, mappedBy: 'newsletter')]
    private Collection $newsletterSubscribers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->newsletterSubscribers = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isCanBePublished(): bool
    {
        return $this->canBePublished;
    }

    public function setCanBePublished(bool $canBePublished): static
    {
        $this->canBePublished = $canBePublished;

        return $this;
    }

    public function isSent(): bool
    {
        return $this->isSent;
    }

    public function setIsSent(bool $isSent): static
    {
        $this->isSent = $isSent;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
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
            $newsletterSubscriber->setNewsletter($this);
        }

        return $this;
    }

    public function removeNewsletterSubscriber(NewsletterSubscriber $newsletterSubscriber): static
    {
        if ($this->newsletterSubscribers->removeElement($newsletterSubscriber)) {
            // set the owning side to null (unless already changed)
            if ($newsletterSubscriber->getNewsletter() === $this) {
                $newsletterSubscriber->setNewsletter(null);
            }
        }

        return $this;
    }
}