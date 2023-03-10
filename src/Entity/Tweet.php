<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TweetRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['tweet:read']],
    denormalizationContext: ['groups' => ['tweet:write']],
)]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id'=>'exact', 'text' => 'partial'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt'])]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tweet:read', 'tweet:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 280)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 280)]
    #[Groups(['tweet:read', 'tweet:write'])]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups(['tweet:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['tweet:read'])]
    private ?int $likeCount = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['tweet:read', 'tweet:write'])]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'tweet', targetEntity: Photo::class)]
    private Collection $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->likeCount = 0;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getLikeCount(): ?int
    {
        return $this->likeCount;
    }

    public function setLikeCount(int $likeCount): self
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Photo $photo): self
    {
        if (!$this->attachments->contains($photo)) {
            $this->attachments->add($photo);
            $photo->setTweet($this);
        }

        return $this;
    }

    public function removeAttachment(Photo $photo): self
    {
        if ($this->attachments->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTweet() === $this) {
                $photo->setTweet(null);
            }
        }

        return $this;
    }

    public function addPhoto(Photo $photo): self {
        return $this->addAttachment($photo);
    }

}
