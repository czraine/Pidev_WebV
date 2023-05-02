<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Reservationcircuit
 *
 * @ORM\Table(name="reservationcircuit", indexes={@ORM\Index(name="sdferezr", columns={"nc"}), @ORM\Index(name="zerzerezr", columns={"id_client"})})
 * @ORM\Entity
 */
class Reservationcircuit
{
    /**
     * @var int
     *
     * @ORM\Column(name="num_res", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue()

     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numRes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer", nullable=false)
     */
    private $nbrPlace;

    /**
     * @var Circuit
     *
     * @ORM\ManyToOne(targetEntity="Circuit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nc", referencedColumnName="nc")
     * })
     */
    private $nc;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_User")
     * })
     */


    private $idClient;
     /**
     * @var boolean
     *
     * @ORM\Column(name="isPaid", type="boolean", nullable=false)
     */
    private $isPaid;

    public function getNumRes(): ?int
    {
        return $this->numRes;
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

    public function getNbrPlace(): ?int
    {
        return $this->nbrPlace;
    }

    public function setNbrPlace(int $nbrPlace): self
    {
        $this->nbrPlace = $nbrPlace;

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

    public function getIdClient(): ?User
    {
        return $this->idClient;
    }

    public function setIdClient(?User $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getIsPaid() : ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }


}
