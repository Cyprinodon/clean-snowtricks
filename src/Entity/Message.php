<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A database entity which represents a user comment.
 *
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @var integer $id The comment's numerical id.
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $content The comment's textual content.
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var Datetime $createdAt The comment's creation date.
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var User $user The comment's author.
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Trick $trick The trick which contains the comment.
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * Gives the comment's id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gives the comment's content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Changes the comment's content.
     *
     * @param string $content The comment's new content.
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gives the comment's creation date.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Changes the comment's creation date.
     *
     * @param DateTimeInterface $createdAt The comment's new creation date.
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gives the comment's associated author.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Changes the comment's associated author.
     *
     * @param User|null $user The comment's new author.
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gives the comment's associated trick.
     *
     * @return Trick|null
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    /**
     * Changes the comment's associated trick.
     *
     * @param Trick|null $trick The comment's new associated trick.
     * @return $this
     */
    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
