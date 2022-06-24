<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="string")
     */
    private $MarketValue;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="companies")
     */
    private $Subscribers;

    public function __construct()
    {
        $this->Subscribers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getMarketValue(): ?string
    {
        return $this->MarketValue;
    }

    public function setMarketValue(string $MarketValue): self
    {
        $this->MarketValue = $MarketValue;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSubscribers(): Collection
    {
        return $this->Subscribers;
    }

    public function addSubscriber(UserInterface $subscriber): self
    {
        if (!$this->Subscribers->contains($subscriber)) {
            $this->Subscribers[] = $subscriber;
        }

        return $this;
    }

    public function removeSubscriber(UserInterface $subscriber): self
    {
        $this->Subscribers->removeElement($subscriber);

        return $this;
    }
}
