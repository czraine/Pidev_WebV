<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Planningcircuit
 *
 * @ORM\Table(name="planningcircuit", indexes={@ORM\Index(name="zerezrezr", columns={"nc"})})
 * @ORM\Entity
 */
class Planningcircuit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var int
     *
     * @ORM\Column(name="capacite", type="integer", nullable=false)
     */
    private $capacite;

    /**
     * @var Circuit
     *
     * @ORM\ManyToOne(targetEntity="Circuit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nc", referencedColumnName="nc")
     * })
     */
    private $nc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getNc(): ?Circuit
    {
        return $this->nc;
    }

    public function setNc(?Circuit $nc): self
    {
        $this->nc = $nc;

        return $this;
    }

    public function __toString()
    {
        return $this->nc;
    }


}
