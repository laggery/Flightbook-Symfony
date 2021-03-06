<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;

/**
 * Place controller.
 *
 * @Route("/place")
 */
class PlaceController extends Controller {

    /**
     * @Route("/autocomplete", name="place_autocomplete")
     */
    public function autocompleteAction(Request $request) {
        $names = array();
        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Place')->autocomplete($this->getUser()->getId(), $term);

        foreach ($entities as $entity) {
            $names[] = $entity->getName();
        }
        
        echo json_encode($names); die;
    }

    /**
     * Lists all Place entities.
     *
     * @Route("/", name="place_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $places = $em->getRepository('AppBundle:Place')->findBy(array('user' => $this->getUser()->getId()));

        return $this->render('place/index.html.twig', array(
                    'places' => $places,
        ));
    }

    /**
     * Creates a new Place entity.
     *
     * @Route("/new", name="place_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $place = new Place();
        $place->setUser($this->getUser());
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $placeExist = $em->getRepository('AppBundle:Place')->findByName($this->getUser()->getId(), $place->getName());
            if (count($placeExist) < 1) {
                $em->persist($place);
                $em->flush();
                return $this->redirectToRoute('place_show', array('id' => $place->getId()));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('message.placeExist'));
            }
        }

        return $this->render('place/new.html.twig', array(
                    'place' => $place,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Place entity.
     *
     * @Route("/{id}", name="place_show")
     * @Method("GET")
     */
    public function showAction(Place $place) {
        $deleteForm = $this->createDeleteForm($place);

        $em = $this->getDoctrine()->getManager();
        $isPlaceUsed = $em->getRepository('AppBundle:Flight')->isPlaceUsed($this->getUser()->getId(), $place->getId());

        return $this->render('place/show.html.twig', array(
                    'place' => $place,
                    'delete_form' => $deleteForm->createView(),
                    'isPlaceUsed' => $isPlaceUsed
        ));
    }

    /**
     * Displays a form to edit an existing Place entity.
     *
     * @Route("/{id}/edit", name="place_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Place $place) {
        $deleteForm = $this->createDeleteForm($place);
        $editForm = $this->createForm(PlaceType::class, $place);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $placeExist = $em->getRepository('AppBundle:Place')->findByName($this->getUser()->getId(), $place->getName());
            if (count($placeExist) < 1) {
                $em->persist($place);
                $em->flush();
                return $this->redirectToRoute('place_show', array('id' => $place->getId()));
            } elseif ($place->getId() == $placeExist[0]->getId()) {
                $em->persist($place);
                $em->flush();
                return $this->redirectToRoute('place_show', array('id' => $place->getId()));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('message.placeExist'));
            }
        }

        return $this->render('place/edit.html.twig', array(
                    'place' => $place,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Place entity.
     *
     * @Route("/{id}", name="place_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Place $place) {
        $form = $this->createDeleteForm($place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush();
        }

        return $this->redirectToRoute('place_index');
    }

    /**
     * Creates a form to delete a Place entity.
     *
     * @param Place $place The Place entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Place $place) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('place_delete', array('id' => $place->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
