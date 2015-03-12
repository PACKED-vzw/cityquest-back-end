<?php

namespace AthenaPlus\CityQuestBundle\Controller;

use Sonata\MediaBundle\Tests\Entity\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AthenaPlus\CityQuestBundle\Entity\Item;
use AthenaPlus\CityQuestBundle\Form\ItemType;

/**
 * Item controller.
 *
 */
class ItemController extends Controller
{

    /**
     * Lists all Item entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CityQuestBundle:Item')->findAll();

        return $this->render('CityQuestBundle:Item:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Item entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Item();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_show', array('id' => $entity->getId())));
        }

        return $this->render('CityQuestBundle:Item:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Item entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('item_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Item entity.
     *
     */
    public function newAction()
    {
        $entity = new Item();
        $form   = $this->createCreateForm($entity);

        return $this->render('CityQuestBundle:Item:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Item entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CityQuestBundle:Item:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CityQuestBundle:Item:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Item entity.
    *
    * @param Item $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity, array(
            'action' => $this->generateUrl('item_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Item entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CityQuestBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('item_edit', array('id' => $id)));
        }

        return $this->render('CityQuestBundle:Item:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Item entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CityQuestBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('item'));
    }

    /**
     * Creates a form to delete a Item entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('item_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * saves an entity
     *
     */
    public function saveAjaxAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        if ($request->get('id')==0){
            $entity = new Item();
            $controlString = uniqid($this->container->getParameter('base_url'));
            $entity->setQrcode($controlString);
        }
        else {
            $entity = $em->getRepository('CityQuestBundle:Item')->find($request->get('id'));
        }

        $entity->setTitle($request->get('title'));
        $entity->setDescription($request->get('description'));
        $entity->setCrypticDescription($request->get('crypticdescription'));
        $entity->setSequence("1");
        $entity->setHints($request->get('hints'));
        $entity->setMedia($request->get('media'));


        // TODO: error handling
        $quest = $em->getRepository('CityQuestBundle:Quest')->find($request->get('questid'));

        $entity->setQuest($quest);

        $em->persist($entity);
        $em->flush();

        $container = $this->container;
        $serializer = $container->get('jms_serializer');
        unset($entity->quest);
        $response = new Response($serializer->serialize($entity, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function loadAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CityQuestBundle:Item')->find($request->get('id'));

        unset($entity->quest);

        $container = $this->container;
        $serializer = $container->get('jms_serializer');
        $response = new Response($serializer->serialize($entity, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function galleryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $media = $mediaManager->findAll();

        return $this->render('CityQuestBundle:Media:mediaPicker.html.twig', array(
            'media' => $media
        ));

    }

    public function printQrAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('CityQuestBundle:Item')->find($id);

        return $this->render('CityQuestBundle:Item:print.html.twig', array(
            'item' => $item
        ));
    }


}
