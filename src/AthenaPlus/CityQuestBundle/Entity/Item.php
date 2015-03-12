<?php

namespace AthenaPlus\CityQuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AthenaPlus\CityQuestBundle\Entity\ItemRepository")
 */
class Item
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(name="hints", type="json_array")
     */
    private $hints;


    /**
     * @var array
     *
     * @ORM\Column(name="media", type="json_array", nullable=true)
     */
    private $media;

    /**
     * @var integer
     *
     * @ORM\Column(name="sequence", type="integer")
     */
    private $sequence;


    /**
     * @var string
     *
     * @ORM\Column(name="qrcode", type="string", length=255)
     */
    private $qrcode;

    /**
     * @var string
     *
     * @ORM\Column(name="cryptic_description", type="text")
     */
    private $crypticDescription;


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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * Set title
     *
     * @param string $title
     * @return Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set qrcode
     *
     * @param string $qrcode
     * @return Item
     */
    public function setQrcode($qrcode)
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    /**
     * Get qrcode
     *
     * @return string
     */
    public function getQrcode()
    {
        return $this->qrcode;
    }

    /**
     * Set hints
     *
     * @param array $hints
     * @return Item
     */
    public function setHints($hints)
    {
        $this->hints = $hints;

        return $this;
    }

    /**
     * Get media
     *
     * @return array 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set media
     *
     * @param array $media
     * @return Item
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get hints
     *
     * @return array
     */
    public function getHints()
    {
        return $this->hints;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     * @return Item
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Quest", inversedBy="items")
     * @ORM\JoinColumn(name="quest_id", referencedColumnName="id")
     */
    public $quest;

    /**
     * Set quest
     *
     * @param Quest $quest
     */
    public function setQuest(Quest $quest = null)
    {
        $this->quest = $quest;
    }

    /**
     * Get quest
     *
     * @return Quest
     */
    public function getQuest()
    {
        return $this->quest;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set crypticDescription
     *
     * @param string $crypticDescription
     * @return Trip
     */
    public function setCrypticDescription($crypticDescription)
    {
        $this->crypticDescription = $crypticDescription;

        return $this;
    }

    /**
     * Get crypticDescription
     *
     * @return string
     */
    public function getCrypticDescription()
    {
        return $this->crypticDescription;
    }






}
