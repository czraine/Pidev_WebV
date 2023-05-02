<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eventsreview
 *
 * @ORM\Table(name="eventsreview", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="fk_event_eventsrev", columns={"Event_id"})})
 * @ORM\Entity
 */
class Eventsreview
{
    /**
     * @var int
     *
     * @ORM\Column(name="Review_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $reviewId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Event_name", type="string", length=30, nullable=true, options={"default"="NULL"})
     */
    private $eventName = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="Review_txt", type="string", length=125, nullable=false)
     */
    private $reviewTxt;

    /**
     * @var \Evenement
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Event_id", referencedColumnName="Event_id")
     * })
     */
    private $event;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_User")
     * })
     */
    private $idUser;

    public function getReviewId(): ?int
    {
        return $this->reviewId;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(?string $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getReviewTxt(): ?string
    {
        return $this->reviewTxt;
    }

    public function setReviewTxt(string $reviewTxt): self
    {
        $this->reviewTxt = $reviewTxt;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
