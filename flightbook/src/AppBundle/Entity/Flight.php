<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Table(name="flight", indexes={@ORM\Index(name="glider_id", columns={"glider_id"}), @ORM\Index(name="landing_id", columns={"landing_id"}), @ORM\Index(name="start_id", columns={"start_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FlightRepository")
 */
class Flight {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="km", type="integer", nullable=true)
     */
    private $km;

    /**
     * @var \Glider
     *
     * @ORM\ManyToOne(targetEntity="Glider")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="glider_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $glider;

    /**
     * @var \Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="start_id", referencedColumnName="id")
     * })
     */
    private $start;
    
    private $startText;

    /**
     * @var \Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="landing_id", referencedColumnName="id")
     * })
     */
    private $landing;
    
    private $landingText;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $user;

    /**
     * 
     */
    public function __construct() {
        $this->date = new \DateTime();
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Flight
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Flight
     */
    public function setTime($time) {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Flight
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Flight
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set km
     *
     * @param integer $km
     *
     * @return Flight
     */
    public function setKm($km) {
        $this->km = $km;

        return $this;
    }

    /**
     * Get km
     *
     * @return integer
     */
    public function getKm() {
        return $this->km;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Flight
     */
    public function setUser(\AppBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set landing
     *
     * @param \AppBundle\Entity\Place $landing
     *
     * @return Flight
     */
    public function setLanding(\AppBundle\Entity\Place $landing = null) {
        $this->landing = $landing;

        return $this;
    }
    
    /**
     * Set landingtext
     *
     * @param $landingText
     *
     * @return Flight
     */
    public function setLandingText($landingText = null) {
        $this->landingText = $landingText;

        return $this;
    }

    /**
     * Get landing
     *
     * @return \AppBundle\Entity\Place
     */
    public function getLanding() {
        return $this->landing;
    }
    
    /**
     * Get landingText
     *
     * @return String
     */
    public function getLandingText() {
        return $this->landingText;
    }

    /**
     * Set start
     *
     * @param \AppBundle\Entity\Place $start
     *
     * @return Flight
     */
    public function setStart(\AppBundle\Entity\Place $start = null) {
        $this->start = $start;

        return $this;
    }
    
    /**
     * Set startText
     *
     * @param $startText
     *
     * @return Flight
     */
    public function setStartText($startText = null) {
        $this->startText = $startText;

        return $this;
    }

    /**
     * Get start
     *
     * @return \AppBundle\Entity\Place
     */
    public function getStart() {
        return $this->start;
    }
    
    /**
     * Get startText
     *
     * @return String
     */
    public function getStartText() {
        return $this->startText;
    }

    /**
     * Set glider
     *
     * @param \AppBundle\Entity\Glider $glider
     *
     * @return Flight
     */
    public function setGlider(\AppBundle\Entity\Glider $glider = null) {
        $this->glider = $glider;

        return $this;
    }

    /**
     * Get glider
     *
     * @return \AppBundle\Entity\Glider
     */
    public function getGlider() {
        return $this->glider;
    }

}
