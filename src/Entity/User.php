<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $join_on = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity:"App\Entity\Project", inversedBy:"users")]
    #[ORM\JoinTable(name:"users_projects")]
    private $projects;

    
    #[ORM\OneToMany(targetEntity:"App\Entity\Task", mappedBy:"user")]
    private $tasks;

    public function __construct(){
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getJoinOn(): ?\DateTimeInterface
    {
        return $this->join_on;
    }

    public function setJoinOn(\DateTimeInterface $join_on): static
    {
        $this->join_on = $join_on;

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

    /**
     * Get the value of projects
     */ 
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set the value of projects
     *
     * @return  self
     */ 
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get the value of tasks
     */ 
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set the value of tasks
     *
     * @return  self
     */ 
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }
}
