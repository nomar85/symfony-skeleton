<?php
// src/AppBundle/Entity/Settings.php

namespace DevPro\adminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="settings")
*/
class Settings
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $logopath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $primary_color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $secondary_color;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set logopath
     *
     * @param string $logopath
     * @return Settings
     */
    public function setLogopath($logopath)
    {
        $this->logopath = $logopath;

        return $this;
    }

    /**
     * Get logopath
     *
     * @return string 
     */
    public function getLogopath()
    {
        return $this->logopath;
    }

    /**
     * Set primary_color
     *
     * @param string $primaryColor
     * @return Settings
     */
    public function setPrimaryColor($primaryColor)
    {
        $this->primary_color = $primaryColor;

        return $this;
    }

    /**
     * Get primary_color
     *
     * @return string 
     */
    public function getPrimaryColor()
    {
        return $this->primary_color;
    }

    /**
     * Set secondary_color
     *
     * @param string $secondaryColor
     * @return Settings
     */
    public function setSecondaryColor($secondaryColor)
    {
        $this->secondary_color = $secondaryColor;

        return $this;
    }

    /**
     * Get secondary_color
     *
     * @return string 
     */
    public function getSecondaryColor()
    {
        return $this->secondary_color;
    }
}
