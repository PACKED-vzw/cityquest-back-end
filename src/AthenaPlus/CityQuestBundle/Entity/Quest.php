<?php

namespace AthenaPlus\CityQuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Quest
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AthenaPlus\CityQuestBundle\Entity\QuestRepository")
 */
class Quest
{
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


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
     * @var string
     *
     * @ORM\Column(name="zoomLevelStaticMap", type="string", length=255)
     */
    private $zoomLevelStaticMap;

    /**
     * @var string
     *
     * @ORM\Column(name="nameOrganisation", type="string", length=255, nullable = true)
     */
    private $nameOrganisation;

    /**
     * @var string
     *
     * @ORM\Column(name="fullAddress", type="text", nullable = true)
     */
    private $fullAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPerson", type="string", length=255, nullable = true)
     */
    private $contactPerson;


    /**
     * @var string
     *
     * @ORM\Column(name="emailAddress", type="string", length=255, nullable = true)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="telephoneNumber", type="string", length=255, nullable = true)
     */
    private $telephoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", nullable = true)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="frontImage", type="text", nullable = true)
     */
    private $frontImage;


    /**
     * @var string
     *
     * @ORM\Column(name="averageDuration", type="string", length=255, nullable = true)
     */
    private $averageDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable = true)
     */
    private $status;


    /**
     * @var string
     *
     * @ORM\Column(name="disclaimer", type="text", nullable = true)
     */
    private $disclaimer;


    /**
     * @var string
     *
     * @ORM\Column(name="startpoint", type="json_array", nullable=true)
     */
    private $startpoint;

    /**
     * @var string
     *
     * @ORM\Column(name="endpoint", type="json_array", nullable=true)
     */
    private $endpoint;


    /**
     * @var string
     *
     * @ORM\Column(name="itemsJson", type="json_array", nullable=true)
     */
    private $itemsJson;

    /**
     * @var string
     *
     * @ORM\Column(name="staticMap", type="text", nullable = true)
     */
    private $staticMap;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var string
     *
     * @ORM\Column(name="publishKey", type="text")
     */
    private $publishKey;


    /**
     * @ORM\ManyToOne(targetEntity="AthenaPlus\UserBundle\Entity\User", inversedBy="quests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * Set user
     *
     * @param User $user
     */
    public function setUser(\AthenaPlus\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
     * Set title
     *
     * @param string $title
     * @return Quest
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
     * Set startpoint
     *
     * @param string $startpoint
     * @return Quest
     */
    public function setStartpoint($startpoint)
    {
        $this->startpoint = $startpoint;
        return $this;
    }

    /**
     * Get startpoint
     *
     * @return string
     */
    public function getStartpoint()
    {
        return $this->startpoint;
    }


    /**
     * Set endpoint
     *
     * @param string $endpoint
     * @return Quest
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Get endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set itemsJson
     *
     * @param string $itemsJson
     * @return Quest
     */
    public function setItemsJson($itemsJson)
    {
        //echo gettype($itemsJson); die;
        //print_r($itemsJson); die;

        //$items = $itemsJson;
        /*foreach ($items as &$item){
            if(array_key_exists('media', $item)&&strlen($item['media'])>5){
                // echo "ahja"; die;

                //print_r($item['media']); die;


                $image = str_replace(' ','+',$item['media']);

                //print_r(substr($image, 1,1)); die;
                //echo(gettype($image)); die;

                if(gettype($image)=="string"){
                    $string = $image;
                }

                $image = explode(',', $string);


                $extension = explode('/', $image[0]);
                $extension = explode(';', $extension[1]);
                $extension = $extension[0];

                $filename = uniqid('img_') . "." .$extension;
                file_put_contents($filename, base64_decode($image[1]));

                $item['image'] = $filename;
                // remove data uri
                unset($item['media']);
            }
        }*/


        //$itemsJson = json_encode($items);


        $this->itemsJson = $itemsJson;
        return $this;
    }

    /**
     * Get itemsJson
     *
     * @return string
     */
    public function getItemsJson()
    {
        return $this->itemsJson;
    }

    /**
     * Set zoomLevelStaticMap
     *
     * @param string $zoomLevelStaticMap
     * @return Quest
     */
    public function setZoomLevelStaticMap($zoomLevelStaticMap)
    {
        $this->zoomLevelStaticMap = $zoomLevelStaticMap;

        return $this;
    }

    /**
     * Get zoomLevelStaticMap
     *
     * @return string
     */
    public function getZoomLevelStaticMap()
    {
        return $this->zoomLevelStaticMap;
    }

    /**
     * Set staticMap
     *
     * @param string $staticMap
     * @return Quest
     */
    public function setStaticMap()
    {

        /**print_r($this->getStartpoint());die;
        $startpoint['lat'] = "";
        $startpoint['lng'] = "";
        $endpoint['lat'] = "";
        $endpoint['lng'] = "";
        $zoomLevel = "";**/

        $startpoint = $this->getStartpoint();
        $endpoint = $this->getEndpoint();
        $zoomLevel = $this->getZoomLevelStaticMap();

        if(array_key_exists('lat', $startpoint)&&array_key_exists('lng', $startpoint)&&array_key_exists('lat', $endpoint)&&array_key_exists('lng', $endpoint)){
            $staticMapUrl = "https://maps.googleapis.com/maps/api/staticmap?center=". $startpoint['lat'] . ",". $startpoint['lng']
                ."&zoom=". $zoomLevel ."&size=3000x6000&maptype=roadmap%20&markers=color:blue|label:S|". $startpoint['lat'] . ",". $startpoint['lng']
                ."&markers=color:green|label:G|". $endpoint['lat'] . ",". $endpoint['lng'];


            // $staticMapUrl = "https://maps.googleapis.com/maps/api/staticmap?center=51.0543422,3.7174242999999&zoom=5&size=600x300&maptype=roadmap%20&markers=color:blue|label:S|51.0543422,3.7174242999999&markers=color:green|label:G|51.2194475,4.4024643";


            $file = file_get_contents($staticMapUrl);
            $filename = "resources/" . uniqid('map_') . ".png";
            file_put_contents($filename, $file);

            $this->staticMap = $filename;
       }


        return $this;
    }

    /**
     * Get staticMap
     *
     * @return string
     */
    public function getStaticMap()
    {
        return $this->staticMap;
    }

    /**
     * Set nameOrganisation
     *
     * @param string $nameOrganisation
     * @return Quest
     */
    public function setNameOrganisation($nameOrganisation)
    {
        $this->nameOrganisation = $nameOrganisation;

        return $this;
    }

    /**
     * Get nameOrganisation
     *
     * @return string 
     */
    public function getNameOrganisation()
    {
        return $this->nameOrganisation;
    }

    /**
     * Set fullAddress
     *
     * @param string $fullAddress
     * @return Quest
     */
    public function setFullAddress($fullAddress)
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }

    /**
     * Get fullAddress
     *
     * @return string 
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * Set contactPerson
     *
     * @param string $contactPerson
     * @return Quest
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    /**
     * Get contactPerson
     *
     * @return string 
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }


    /**
     * Set publishKey
     *
     * @param string $publishKey
     * @return Quest
     */
    public function setPublishKey($publishKey)
    {
        $this->publishKey = $publishKey;

        return $this;
    }

    /**
     * Get publishKey
     *
     * @return string
     */
    public function getPublishKey()
    {
        return $this->publishKey;
    }


    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return Quest
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set telephoneNumber
     *
     * @param string $telephoneNumber
     * @return Quest
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    /**
     * Get telephoneNumber
     *
     * @return string 
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Quest
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set frontImage
     *
     * @param string $frontImage
     * @return Quest
     */
    public function setFrontImage($frontImage)
    {

        // base64_encode(file_get_contents("../images/folder16.gif"))

        $frontImage = str_replace(' ','+',$frontImage);

        // todo: decode to png image and store on server
        $frontImage = explode(',', $frontImage);


        $extension = explode('/', $frontImage[0]);
        $extension = explode(';', $extension[1]);
        $extension = $extension[0];

        $filename = "resources/" . uniqid('img_') . "." .$extension;
        file_put_contents($filename, base64_decode($frontImage[1]));

        $this->frontImage = $filename;




        /*
        $ifp = fopen('image2.png', "wb");


        fwrite($ifp, base64_decode($frontImage[1]));
        fclose($ifp);*/

        return $this;
    }

    /**
     * Get frontImage
     *
     * @return string
     */
    public function getFrontimage()
    {

        return $this->frontImage;
        // to do: dit hieronder aanpassen .....
        $path = $this->frontImage;

        $path = 'image.png';

        $type = pathinfo($path, PATHINFO_EXTENSION);

        $data = file_get_contents($path);



        return 'data:image/' . $type . ';base64,' . base64_encode($data);

        //return  $frontImage = str_replace('+',' ', $this->frontImage);

        // encode to data uri
        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($this->frontImage));
    }



    /**
     * Set averageDuration
     *
     * @param string $averageDuration
     * @return Quest
     */
    public function setAverageDuration($averageDuration)
    {
        $this->averageDuration = $averageDuration;

        return $this;
    }

    /**
     * Get averageDuration
     *
     * @return string 
     */
    public function getAverageDuration()
    {
        return $this->averageDuration;
    }


    /**
     * Set status
     *
     * @param string $status
     * @return Quest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }




    /**
     * Set disclaimer
     *
     * @param string $disclaimer
     * @return Quest
     */
    public function setDisclaimer($disclaimer)
    {
        $this->disclaimer = $disclaimer;

        return $this;
    }

    /**
     * Get disclaimer
     *
     * @return string 
     */
    public function getDisclaimer()
    {
        return $this->disclaimer;
    }


    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="quest", cascade={"remove"})
     */
    protected $items;

    /**
     * Add item
     *
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Get items
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    // http://sonata-project.org/blog/2013/10/11/mediabundle-mediatype-improved

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $media;

    /**
     * @param MediaInterface $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set published
     *
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }


}
