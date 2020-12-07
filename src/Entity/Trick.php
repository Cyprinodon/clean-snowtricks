<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * A database entity which represents a trick.
 *
 * @UniqueEntity("name")
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 */
class Trick
{
    /**
     * @var integer $id the numerical id of a trick.
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $name The displayed name of a trick.
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string $description The textual content describing a trick.
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var datetime $createdAt The time at which the trick was created/modified.
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var User $user The user who created the trick.
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Category $category The group in which the trick belongs.
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var ArrayCollection $images A collection of Image objects which are linked to the trick.
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @var ArrayCollection $messages A collection of Message objects which are linked to the trick.
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * @var ArrayCollection $videos A collection of Video objects which are linked to the trick.
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", cascade={"persist", "remove"})
     */
    private $videos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * Trick constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /**
     * Retrieves the trick's id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gives the trick's name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Changes the trick's name.
     *
     * @param string $name The name that will replace the current one.
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gives the trick's description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Changes the trick's description.
     *
     * @param string|null $description The description that will replace the current one.
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gives the trick's creation date.
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Changes the trick's creation date.
     *
     * @param DateTimeInterface $createdAt The date that will replace the current one.
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gives the trick's author.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Changes the trick's author.
     *
     * @param User|null $user The user that will replace the current one.
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gives the trick's group.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Changes the trick's group.
     *
     * @param Category|null $category The category that will replace the current one.
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Gives all the trick's images.
     *
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * Adds an image to the trick.
     *
     * @param Image $image The image to add.
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTrick($this);
        }

        return $this;
    }

    /**
     * Removes an image from the trick.
     *
     * @param Image $image The image to remove.
     * @return $this
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * Gives all the trick's messages.
     *
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * Adds a message to the trick.
     *
     * @param Message $message The message to add.
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTrick($this);
        }

        return $this;
    }

    /**
     * Removes a message from the trick.
     *
     * @param Message $message The message to remove.
     * @return $this
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getTrick() === $this) {
                $message->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * Gives all the trick's videos.
     *
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * Adds a video to the trick.
     *
     * @param Video $video The video to add.
     * @return $this
     */
    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    /**
     * Removes a video from the trick
     * @param Video $video The video to remove.
     * @return $this
     */
    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
