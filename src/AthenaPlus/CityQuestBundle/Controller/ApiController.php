<?php

namespace AthenaPlus\CityQuestBundle\Controller;

use AthenaPlus\CityQuestBundle\Entity\Item;
use AthenaPlus\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AthenaPlus\CityQuestBundle\Entity\Quest;
use AthenaPlus\CityQuestBundle\Form\QuestType;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * API controller.
 *
 */
class ApiController extends Controller
{
    protected $allowable_tags = '<p><b><u><strong><em><i>';
    /**
     * updates Quest
     *
     */
    public function updateAction(Request $request,  Quest $quest)
    {
        //print_r($request->get('data')); die;

        $reply = $request->getContent ();
        $reply = substr ($reply, 5);
        /* $reply is always UTF-8 -> JSON */
        $details = json_decode ($reply, true);

        //print_r($details); die;

        //$details['contact'] = (array) $details['contact'];
        //print_r($details); die;
        //print_r($_POST); die;
        // http://stackoverflow.com/questions/19254029/angularjs-http-post-does-not-send-data

        //print_r($details); die;

        $quest->setTitle($details['name']);

        if (isset($details['organisation'])) {
            $quest->setNameOrganisation(strip_tags ($details['organisation'], $this->allowable_tags));
        }

        if (isset($details['address'])) {
            $quest->setFullAddress(strip_tags ($details['address'], $this->allowable_tags));
        }

        if (isset($details['duration'])) {
            $quest->setAverageDuration($details['duration']);
        }

        if (isset($details['disclaimer'])){
            $quest->setDisclaimer(strip_tags ($details['disclaimer'], $this->allowable_tags));
        }

        if (isset($details['abstract'])){
            $quest->setAbstract(strip_tags ($details['abstract'], $this->allowable_tags));
        }

        if(isset($details['contact']['name'])) {
            $quest->setContactPerson(strip_tags ($details['contact']['name'], $this->allowable_tags));
        }

        if (isset($details['contact']['email']))
        {
            $quest->setEmailAddress(strip_tags ($details['contact']['email'], $this->allowable_tags));
        }

        if (isset($details['contact']['telephone'])){
            $quest->setTelephoneNumber(strip_tags ($details['contact']['telephone'], $this->allowable_tags));
        }

        if (isset($details['status'])){
            $quest->setStatus($details['status']);
        }

        if (isset($details['image'])){
            $quest->setFrontImage($details['image']);
        }

        if (isset($details['map']['url'])){
            $quest->setStaticMap($details['map']['url']);
        }
        if (isset($details['map']['startpoint'])) {
            $quest->setStartpoint($details['map']['startpoint']);
        }
        if (isset($details['map']['endpoint'])) {
            $quest->setEndpoint($details['map']['endpoint']);
        }
        if (isset($details['map']['zoomLevel'])){
            $quest->setZoomLevelStaticMap($details['map']['zoomLevel']);
        }

        if (isset($details['items'])){
            /*/*/
            array_walk_recursive ($details['items'], function (&$value) {
                $value = strip_tags ($value, $this->allowable_tags);
            });
            /* Generate base64-encoded image files for the mobile app for every image in $details['items'] */
            $new_items = array ();
            foreach ($details['items'] as $item) {
                $img_loc = $item['image'];
                $image = file_get_contents ($img_loc);
                $resp = array (base64_encode ($image));
                file_put_contents ($img_loc.'.json', json_encode ($resp));
                if (isset ($item['64_image'])) {
                    unset ($item['64_image']);
                }
                $new_hints = array ();
                foreach ($item['hints'] as $hint) {
                    if (isset ($hint['64_image'])) {
                        unset ($hint['64_image']);
                    }
                    array_push ($new_hints, $hint);
                }
                $item['hints'] = $new_hints;
                array_push ($new_items, $item);
            }
            $details['items'] = $new_items;
            $quest->setItemsJson(json_encode ($details['items']));
        }
        $quest->setStaticMap('hallo');

        $em = $this->getDoctrine()->getManager();
        $em->persist($quest);
        $em->flush();

        return new JsonResponse($quest);
    }

    /**
     * Load quest
     */
    public function loadQuestAction(Quest $quest)
    {

        /*if (!$this->get('security.context')->isGranted('ROLE_ADMIN')){
            if(($this->getUser()!=$quest->getUser())){
                throw new Exception('Not a quest of mine...');
            }
        }*/
        /*
        if(($this->getUser()!=$quest->getUser())){
            throw new Exception('Not a quest of mine...');
        }
        */

        return new JsonResponse($this->orderQuest($quest));
    }


    /**
     * Load quest
     */
    public function deleteAction(Quest $quest)
    {
        if($this->getUser()!=$quest->getUser()){
            throw new Exception('Not a quest of mine...');
        }

        $em = $this->getDoctrine()->getManager();

        if (!$quest) {
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }

        $em->remove($quest);
        $em->flush();

        return new Response("ok");
    }



    // todo dit moet naar service: http://symfony.com/doc/current/book/service_container.html of via
    // http://symfony.com/doc/current/cookbook/form/data_transformers.html
    protected function orderQuest(Quest $quest){
        $orderedQuest['cityquestProvider'] = array ('url' => 'http://cityquest.be'); /* For portability */
        $orderedQuest['details']['id'] = $quest->getId();
        $orderedQuest['details']['name'] = $quest->getTitle();
        $orderedQuest['details']['organisation'] = $quest->getNameOrganisation();
        $orderedQuest['details']['address'] = $quest->getFullAddress();
        $orderedQuest['details']['duration'] = $quest->getAverageDuration();
        $orderedQuest['details']['disclaimer'] = $quest->getDisclaimer();
        $orderedQuest['details']['abstract'] = $quest->getAbstract();
        $orderedQuest['details']['publishkey'] = $quest->getPublishKey();
        $orderedQuest['details']['published'] = $quest->getPublished();
        $orderedQuest['details']['status'] = $quest->getStatus();

        $orderedQuest['details']['imageFile'] = $quest->getFrontImage();
        $orderedQuest['details']['remote_imageFile'] = 'http://cityquest.be/'.$orderedQuest['details']['imageFile'];
        $orderedQuest['details']['contact']['name'] = $quest->getContactPerson();
        $orderedQuest['details']['contact']['email'] = $quest->getEmailAddress();
        $orderedQuest['details']['contact']['telephone'] = $quest->getTelephoneNumber();

        $orderedQuest['details']['map']['zoom'] = $quest->getZoomLevelStaticMap();
        $orderedQuest['details']['map']['url'] = $quest->getStaticMap();
        // these are json objects
        $orderedQuest['details']['map']['startpoint'] = $quest->getStartpoint();
        $orderedQuest['details']['map']['endpoint'] = $quest->getEndpoint();


        $orderedQuest['details']['items'] = $quest->getItemsJson();
        $new_items = array ();
        foreach (json_decode ($orderedQuest['details']['items'], true) as $item) {
            $img_loc = $item['image'];
            $image = file_get_contents ($img_loc);
            $resp = array (base64_encode ($image));
            file_put_contents ($img_loc.'.json', json_encode ($resp));
            if (isset ($item['64_image'])) {
                unset ($item['64_image']);
            }
            $new_hints = array ();
            foreach ($item['hints'] as $hint) {
                if (isset ($hint['64_image'])) {
                    unset ($hint['64_image']);
                }
                $hint['remote_image'] = 'http://cityquest.be/'.$hint['image'];
                array_push ($new_hints, $hint);
            }
            $item['hints'] = $new_hints;
            $item['remote_image'] = 'http://cityquest.be/'.$item['image'];
            array_push ($new_items, $item);
        }
        $orderedQuest['details']['items'] = json_encode ($new_items);
        return $orderedQuest;
    }


    /**
     * add item
     *
     */
    public function addItemAction(Request $request,  Quest $quest)
    {
        $item = new Item();

        // todo: allerhande checks !?
        $item->setTitle($request->get('title'));
        $item->setCrypticDescription($request->get('crypticDescription'));
        $item->setHints(array());
        $item->setQuest($quest);
        $item->setDescription($request->get('description'));
        $item->setSequence($request->get('sequence'));
        $item->setQrcode(uniqid());


        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return new JsonResponse($quest->getItemsJson());

    }

    public function publishAction(Quest $quest)
    {
        if($quest->getPublished()){
            $quest->setPublished(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($quest);
            $em->flush();
            return new JsonResponse(array('published' => false));
        }
        else {
            $quest->setPublished(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($quest);
            $em->flush();
            return new JsonResponse(array('published' => true));
        }

    }


    public function convertBase64toFileAction(Request $request)
    {
        $dataString = $request->get('base64');
        $dataString = str_replace(' ','+',$dataString);

        //print_r(substr($image, 1,1)); die;
        //echo(gettype($image)); die;
        /*if(gettype($image)=="string"){
            $string = $image;
        }*/

        $image = explode(',', $dataString);
        $extension = explode('/', $image[0]);
        $extension = explode(';', $extension[1]);
        $extension = $extension[0];

        $filename = "resources/" . uniqid('img_') . "." .$extension;
        file_put_contents($filename, base64_decode($image[1]));

        return new Response($filename);
    }





    public function getQuestDataAction($key)
    {
        //$key = 'piviyawosa';
        // todo: check if quest is published ...
        $em = $this->getDoctrine()->getManager();
        $quest = $em->getRepository('CityQuestBundle:Quest')->findByPublishKey($key);
        if(!$quest[0]->getPublished()){
            throw new Exception("Quest not published");
        }

        return new JsonResponse($this->orderQuest($quest[0]));



        //return new Response(json_encode($quest), 201, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'));
        //return new JsonResponse($this->orderQuest($quest[0]), 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'));
    }


    /*
     * Function to convert the item images to base64 and wrap them in a JSON-array in a JSON-file for retrieval by the API
     * @param string $image_location
     * @return string json-array
     */
    public function convertResourceImageToBase64 ($image_location) {
        $json_file = $image_location.'.json';
        if (!file_exists ($image_location)) {
            throw new Exception ("Error: image $image_location does not exist");
        }
        $resp = null;
        if (file_exists ($json_file)) {
            $resp = json_decode (file_get_contents ($json_file), true);
            return $resp[0];
        }
        $resp = array (base64_encode (file_get_contents ($image_location)));
        file_put_contents ($json_file, json_encode ($resp));
        return $resp[0];
    }

    /*
     * Function that returns a base64-encoded image for use in the Mobile App (caching)
     * @param string $id - image name
     * @return jsonResponse $json
     */
    public function getImageAction ($id) {
        $img_loc = 'resources';
        if (!file_exists ($img_loc.'/'.$id)) {
            return new JsonResponse (null);
        }
        return new JsonResponse (array ($this->convertResourceImageToBase64 ($img_loc.'/'.$id)));
    }



    /**
     * upload image
     *
     */
    public function uploadImageAction(Request $request,  Quest $quest)
    {
        return new Response('true');



       /* $image = $request->get('image');
        // http://w3facility.info/question/php-data-uri-to-file/
        $image = str_replace(' ','+',$image);
        $frontImage = explode(',', $image);

        file_put_contents("image3.png", base64_decode($frontImage[1]));





        echo '<img src="$image">'; die;*/
    }




}
