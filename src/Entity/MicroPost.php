<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string" , length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=10 , maxMessage="Predugacko")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User" , inversedBy="posts")
     * @ORM\JoinColumn(nullable=false , onDelete="CASCADE")
     */
    private $user;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User" , inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id" , referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id" , referencedColumnName="id")})
     */
    private $likedBy;


    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPesist(): void
    {
        $this->time = new \DateTime();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        if ($this->likedBy->contains($user)){
            return;
        }
        $this->likedBy->add($user);
    }





}
