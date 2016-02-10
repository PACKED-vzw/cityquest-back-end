<?php

namespace AthenaPlus\CityQuestBundle\Controller;

use AthenaPlus\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AthenaPlus\CityQuestBundle\Entity\Quest;
use AthenaPlus\CityQuestBundle\Form\QuestType;
use Symfony\Component\Config\Definition\Exception\Exception;

use FOS\UserBundle\Util;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;



/**
 * Quest controller.
 *
 */
class QuestController extends Controller
{

    /**
     * Lists all Quest entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CityQuestBundle:Quest')->findAll();

        return $this->render('CityQuestBundle:Quest:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Landing page
     *
     */
    public function landingAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CityQuestBundle:Quest')->findAll();

        return $this->render('CityQuestBundle:CityQuest:landing.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * API test
     *
     */
    public function apiTestAction()
    {
        /*$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('CityQuestBundle:Quest')->findAll();*/

        $neparray = array("title" => "dit is een titel", "author" => "dit is een auteur");
        $result[] = $neparray;
        $result[] = $neparray;

        return new JsonResponse($result);
        /*return $this->render('CityQuestBundle:CityQuest:landing.html.twig', array(
            'entities' => $entities,
        ));*/
    }


    /**
     * API test
     */
    public function apiTest2Action(Request $request)
    {
        if (!(is_numeric($id = $request->get('id')))){
            $id = 1;
            //throw new Exception("That is not a valid id");
        }

        $em = $this->getDoctrine()->getManager();
        $quest = $em->getRepository('CityQuestBundle:Quest')->find($id);
        if(!$quest){
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }
        $quest = $this->orderQuest($quest);
        return new JsonResponse($quest);


        //print_r($quest); die;
        $neparray = array("title" => "dit is een titel", "author" => "dit is een auteur");
        $result[] = $neparray;
        $result[] = $neparray;


        return new JsonResponse($result);
        /*return $this->render('CityQuestBundle:CityQuest:landing.html.twig', array(
            'entities' => $entities,
        ));*/
    }

    // todo dit moet naar service: http://symfony.com/doc/current/book/service_container.html of via
    // http://symfony.com/doc/current/cookbook/form/data_transformers.html
    protected function orderQuest(Quest $quest){


        // niet gebruiken
        $orderedQuest['details']['id'] = $quest->getId();
        $orderedQuest['details']['name'] = $quest->getTitle();
        $orderedQuest['details']['organisation'] = $quest->getNameOrganisation();
        $orderedQuest['details']['address'] = $quest->getFullAddress();
        $orderedQuest['details']['duration'] = $quest->getAverageDuration();
        $orderedQuest['details']['disclaimer'] = $quest->getDisclaimer();
        $orderedQuest['details']['abstract'] = $quest->getAbstract();
        // niet gebruiken
        $orderedQuest['details']['contact']['name'] = $quest->getContactPerson();
        $orderedQuest['details']['contact']['email'] = $quest->getEmailAddress();
        $orderedQuest['details']['contact']['telephone'] = $quest->getTelephoneNumber();

        return $orderedQuest;
    }


    /**
     * API test
     *
     */
    public function apiPostTestAction(Request $request)
    {
        /*$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CityQuestBundle:Quest')->findAll();*/


        /*return $this->render('CityQuestBundle:CityQuest:landing.html.twig', array(
            'entities' => $entities,
        ));*/


        $title  = $request->get('title');
        $author = $request->get('author');

        // write items

        return new JsonResponse(array("title" => $title, "author" => $author ));

    }

    /**
     * Signup
     *
     */
    public function signupAction(Request $request)
    {
        // validate max

        $container = $this->container;
        $userManager = $container->get('fos_user.user_manager');

        /*$em = $this->getDoctrine()->getManager();

        $um = new Util\UserManipulator($userManager);
        $user = $um->create("aapke", "peerke", "joris@pakkentk.be", true, false);
        $em->persist($user);
        $em->flush();*/


        $user = $userManager->createUser();


        $user->setUsername($request->get('cq-name'));
        // todo: set password rules
        $user->setPlainPassword($request->get('cq-password'));
        $user->setEmail($request->get('cq-email'));
        $user->setOrganisation($request->get('cq-organisation'));
        $user->setAddress($request->get('cq-address'));
        $user->setEnabled(true);


        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging
             */
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $userManager->updateUser($user);

        $this->getDoctrine()->getManager()->flush();

        // echo $user->getUsername(); die;


        /*
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.context')->setToken($token);
        $this->get('session')->set('_security_main',serialize($token));
        */
        $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
        $this->get("security.context")->setToken($token); //now the user is logged in

        //now dispatch the login event
        $request = $this->get("request");
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        // redirect to overview page
        return new Response($this->listAction());

    }


    public function showProfileAction()
    {
        return $this->render('CityQuestBundle:CityQuest:profile.html.twig', array(

        ));
    }


    public function editProfileAjaxAction(Request $request)
    {
        //print_r($request->get('full_name')); die;
        $user = $this->getUser();


        $container = $this->container;
        $userManager = $container->get('fos_user.user_manager');

        $user->setUsername($request->get('full_name'));
        // todo: set password rules
        $user->setPlainPassword($request->get('password'));
        $user->setEmail($request->get('email'));
        $user->setOrganisation("test");
        $user->setAddress("test");


        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging
             */
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $userManager->updateUser($user);

        $this->getDoctrine()->getManager()->flush();

        return new Response('true');

    }



    /**
     * List
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();


        $entities = $this->getUser()->getQuests();

        return $this->render('CityQuestBundle:CityQuest:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Manage page
     *
     */
    public function manageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CityQuestBundle:Quest')->findAll();

        return $this->render('CityQuestBundle:CityQuest:manage.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Creates a new Quest entity.
     *
     */
    public function createQuestAction()
    {
        $quest = new Quest();
        $user = $this->getUser();



        $quest->setTitle("Untitled");
        $quest->setZoomLevelStaticMap("5");
        $quest->setPublished(false);
        $quest->setUser($user);

        // todo: change to make key public ....
        $quest->setPublishKey($this->createRandomReadableString(10));

        $em = $this->getDoctrine()->getManager();
        $em->persist($quest);
        $em->flush();

        return new Response($this->listAction());
    }


    private function createRandomReadableString($length = 6){
        $conso=array("b","c","d","f","g","h","j","k","l",
            "m","n","p","r","s","t","v","w","x","y","z");
        $vocal=array("a","e","i","o","u");
        $password="";
        srand ((double)microtime()*1000000);
        $max = $length/2;
        for($i=1; $i<=$max; $i++)
        {
            $password.=$conso[rand(0,19)];
            $password.=$vocal[rand(0,4)];
        }
        return $password;
    }


    /**
     * Creates a new Quest entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Quest();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quest_show', array('id' => $entity->getId())));
        }

        return $this->render('CityQuestBundle:Quest:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Create a new Quest entity with data from the datahub
     */
    public function createDatahubQuestAction(Request $request)
    {
        $quest = new Quest();
        $user = $this->getUser();

        $quest->setTitle("Datahub Showcase");
        $quest->setZoomLevelStaticMap("5");
        $quest->setPublished(false);
        $quest->setUser($user);
        $quest->setItemsJson(json_encode($this->get('datahub')->getFromDatahub()));

        // todo: change to make key public ....
        $quest->setPublishKey($this->createRandomReadableString(10));

        $em = $this->getDoctrine()->getManager();
        $em->persist($quest);
        $em->flush();

        return new Response($this->listAction());
    }

    /**
    * Creates a form to create a Quest entity.
    *
    * @param Quest $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Quest $entity)
    {
        $form = $this->createForm(new QuestType(), $entity, array(
            'action' => $this->generateUrl('quest_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Quest entity.
     *
     */
    public function newAction()
    {
        $entity = new Quest();
        $form   = $this->createCreateForm($entity);

        return $this->render('CityQuestBundle:Quest:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Quest entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Quest')->find($id);
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $media = $mediaManager->findAll();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CityQuestBundle:Quest:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'media'       => $media,
        ));
    }

    /**
     * Provides json for quest
     *
     */
    public function getJsonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CityQuestBundle:Quest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }

        $container = $this->container;
        $serializer = $container->get('jms_serializer');

        $response = new Response($serializer->serialize($entity, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }




    /**
     * Displays a form to edit an existing Quest entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Quest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CityQuestBundle:Quest:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Quest entity.
    *
    * @param Quest $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Quest $entity)
    {
        $form = $this->createForm(new QuestType(), $entity, array(
            'action' => $this->generateUrl('quest_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Quest entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Quest')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quest entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('quest_edit', array('id' => $id)));
        }

        return $this->render('CityQuestBundle:Quest:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Quest entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CityQuestBundle:Quest')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quest entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quest'));
    }

    /**
     * Creates a form to delete a Quest entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quest_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Loads items linked to a quest
     *
     */
    public function loadItemsAction(Request $request){
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $quest = $em->getRepository('CityQuestBundle:Quest')->find($id);

        return $this->render('CityQuestBundle:Quest:items.html.twig', array(
            'items'      => $quest->getItems(),

        ));

    }

}
