<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 30)]
  private ?string $fName = null;

  #[ORM\Column(length: 30)]
  private ?string $lName = null;

  #[ORM\Column(length: 50)]
  private ?string $emailId = null;

  #[ORM\Column(length: 255)]
  private ?string $password = null;

  #[ORM\Column(type: Types::ARRAY , nullable: true)]
  private array $interest = [];

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFName(): ?string
  {
    return $this->fName;
  }

  public function setFName(string $fName): self
  {
    $this->fName = $fName;

    return $this;
  }

  public function getLName(): ?string
  {
    return $this->lName;
  }

  public function setLName(string $lName): self
  {
    $this->lName = $lName;

    return $this;
  }

  public function getEmailId(): ?string
  {
    return $this->emailId;
  }

  public function setEmailId(string $emailId): self
  {
    $this->emailId = $emailId;

    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getInterest(): array
  {
    return $this->interest;
  }

  public function setInterest(?array $interest): self
  {
    $this->interest = $interest;

    return $this;
  }

  public function setter(array $data): self
  {
    $this->setFName($data["fName"]);
    $this->setLName($data["lName"]);
    $this->setEmailId($data["emailId"]);
    $this->setPassword(md5($data["password"]));
    if (isset($data["interest"])) {
      $this->setInterest($data["interest"]);
    }
    return $this;
  }
}
