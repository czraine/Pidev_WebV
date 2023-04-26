<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;




/**
 * Placetovisit
 *
 * @ORM\Table(name="placetovisit")
 * @ORM\Entity
 */
class Placetovisit
{
    /**
     * @var int
     *
     * @ORM\Column(name="Place_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $placeId;

    /**
     * @var string
     *
     * @ORM\Column(name="Place_Name", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="The place name cannot be blank.")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "The place name cannot be longer than {{ limit }} characters."
     * )
     */
    private $placeName;

    /**
     * @var string
     *
     * @ORM\Column(name="CityName", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="The city name cannot be blank.")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "The city name cannot be longer than {{ limit }} characters."
     * )
     */
    private $cityname;

    /**
     * @var string
     *
     * @ORM\Column(name="Place_Type", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="The place type cannot be blank.")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "The place type cannot be longer than {{ limit }} characters."
     * )
     */
    private $placeType;

    /**
     * @var string
     *
     * @ORM\Column(name="Place_Description", type="string", length=230, nullable=false)
     * @Assert\NotBlank(message="The place description cannot be blank.")
     * @Assert\Length(
     *      max = 230,
     *      maxMessage = "The place description cannot be longer than {{ limit }} characters."
     * )
     */
    private $placeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="Place_Address", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="The place address cannot be blank.")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The place address cannot be longer than {{ limit }} characters."
     * )
     */
    private $placeAddress;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Tickets_Price", type="float", precision=10, scale=0, nullable=true)
     * @Assert\PositiveOrZero(message="The ticket price cannot be negative.")
     */
    private $ticketsPrice = NULL;

    /**
     * @var string
     *
     * @ORM\Column(name="Place_Img", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message="The place image cannot be blank.")
     * @Assert\Length(
     *      max = 120,
     *      maxMessage = "The place image path cannot be longer than {{ limit }} characters."
     * )
     */

 /**
* @var string
*
* @ORM\Column(name="Place_Img", type="string", length=120, nullable=false)
*/
     
    private $placeImg;

    /**
* @var string
*
* @ORM\Column(name="Place_Img", type="string", length=120, nullable=false)
*/


/**
 * @var string
 *
 * @ORM\Column(name="Place_img2", type="string", length=120, nullable=false)

 */
private $placeImg2;

/**
 * @var string
 *
 * @ORM\Column(name="Place_Img3", type="string", length=120, nullable=false)
 */
private $placeImg3;

    public function getPlaceId(): ?int
    {
        return $this->placeId;
    }

    public function getPlaceName(): ?string
    {
        return $this->placeName;
    }

    public function setPlaceName(string $placeName): self
    {
        $this->placeName = $placeName;

        return $this;
    }

    public function getCityname(): ?string
    {
        return $this->cityname;
    }

    public function setCityname(string $cityname): self
    {
        $this->cityname = $cityname;

        return $this;
    }

    public function getPlaceType(): ?string
    {
        return $this->placeType;
    }

    public function setPlaceType(string $placeType): self
    {
        $this->placeType = $placeType;

        return $this;
    }

    public function getPlaceDescription(): ?string
    {
        return $this->placeDescription;
    }

    public function setPlaceDescription(string $placeDescription): self
    {
        $this->placeDescription = $placeDescription;

        return $this;
    }

    public function getPlaceAddress(): ?string
    {
        return $this->placeAddress;
    }

    public function setPlaceAddress(string $placeAddress): self
    {
        $this->placeAddress = $placeAddress;

        return $this;
    }

    public function getTicketsPrice(): ?float
    {
        return $this->ticketsPrice;
    }

    public function setTicketsPrice(?float $ticketsPrice): self
    {
        $this->ticketsPrice = $ticketsPrice;

        return $this;
    }

    public function getPlaceImg(): ?string
    {
        return $this->placeImg;
    }

    public function setPlaceImg(string $placeImg): self
    {
        $this->placeImg = $placeImg;

        return $this;
    }

    public function getPlaceImg2(): ?string
    {
        return $this->placeImg2;
    }

    public function setPlaceImg2(string $placeImg2): self
    {
        $this->placeImg2 = $placeImg2;

        return $this;
    }

    public function getPlaceImg3(): ?string
    {
        return $this->placeImg3;
    }

    

    public function setPlaceImg3(string $placeImg3): self
    {
        $this->placeImg3 = $placeImg3;

        return $this;
    }
        /**
    * @return string
    */
    public function __toString()
    {
        return(string)$this->getPlaceName();
    }

    /**
    * @param $placeId
    */
    public function setPlaceId($placeId) {
    	$this->placeId = $placeId;
    }
}
