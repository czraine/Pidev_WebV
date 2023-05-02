<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="favplaces", indexes={
 *     @ORM\Index(name="favuser_idUser", columns={"id_User"}),
 *     @ORM\Index(name="favplace_idplace", columns={"Place_Id"})
 * })
 */
class Favplaces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Placetovisit")
     * @ORM\JoinColumn(name="Place_Id", referencedColumnName="Place_Id")
     */
    private $idPlace;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_User", referencedColumnName="id_User")
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function __toString(): string
    {
        return (string) $this->getUser()->getIdUser();
    }

    public function getIdUser() {
    	return $this->idUser;
    }

    /**
    * @param $idUser
    */
    public function setIdUser($idUser) {
    	$this->idUser = $idUser;
    }

    public function getIdPlace() {
    	return $this->idPlace;
    }

    /**
    * @param $idPlace
    */
    public function setIdPlace($idPlace) {
    	$this->idPlace = $idPlace;
    }
}
