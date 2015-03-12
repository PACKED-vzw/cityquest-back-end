<?php
namespace AthenaPlus\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @var string
     *
     * @ORM\Column(name="organisation", type="string", length=255, nullable = true)
     */
    protected $organisation;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "The address should be at least {{ limit }} characters long.",
     *      maxMessage = "The address cannot be longer than {{ limit }} characters long"
     * )
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @ORM\OneToMany(targetEntity="AthenaPlus\CityQuestBundle\Entity\Quest", mappedBy="user")
     */
    protected $quests;


    /**
     * Add quest
     *
     * @param AthenaPlus\CityQuestBundle\Entity\Quest $quest
     */
    public function addQuest($quest)
    {
        $this->quests[] = $quest;
    }

    /**
     * Get quests
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getQuests()
    {
        return $this->quests;
    }

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
     * Set organisation
     *
     * @param string $organisation
     * @return User
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }


    /**
     * Set address
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }




}
