<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postDate;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     */
    private $headline;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $text;

    // для того чтобы добавляло текущее время в БД
    public function __construct()
    {
        $this->postDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->postDate;
    }

    public function setPostDate(\DateTimeInterface $postDate): self
    {
        $this->postDate = $postDate;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): self
    {
        $this->headline = $headline;

        // чтоб обновлялось время после редактирования
        if($headline !== null){
            $this->postDate = new \DateTime();
        }

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        // чтоб обновлялось время после редактирования
        if($text !== null){
            $this->postDate = new \DateTime();
        }

        return $this;
    }
}
