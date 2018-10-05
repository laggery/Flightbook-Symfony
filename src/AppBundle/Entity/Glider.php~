<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Glider
 *
 * @ORM\Table(name="glider", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GliderRepository")
 */
class Glider {

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
     * @ORM\Column(name="buy_date", type="date", nullable=true)
     */
    private $buyDate;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=30, nullable=false)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tandem", type="boolean", nullable=false)
     */
    private $tandem = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * 
     */
    public function __construct() {
        $this->buyDate = new \DateTime();
        $this->tandem = false;
    }

    /**
     * Set buyDate
     *
     * @param \DateTime $buyDate
     *
     * @return Glider
     */
    public function setBuyDate($buyDate) {
        $this->buyDate = $buyDate;

        return $this;
    }

    /**
     * Get buyDate
     *
     * @return \DateTime
     */
    public function getBuyDate() {
        return $this->buyDate;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Glider
     */
    public function setBrand($brand) {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand() {
        return $this->brand;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Glider
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set tandem
     *
     * @param boolean $tandem
     *
     * @return Glider
     */
    public function setTandem($tandem) {
        $this->tandem = $tandem;

        return $this;
    }

    /**
     * Get tandem
     *
     * @return boolean
     */
    public function getTandem() {
        return $this->tandem;
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
     * @return Glider
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
     * 
     * @return text
     */
    public function __toString() {
        return $this->brand . ' ' . $this->name;
    }

}
