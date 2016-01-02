<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Flight;
use AppBundle\Form\FlightType;

/**
 * Flight controller.
 *
 * @Route("/flight")
 */
class FlightController extends Controller {

    /**
     * Lists all Flight entities.
     *
     * @Route("/", name="flight_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $flights = $em->getRepository('AppBundle:Flight')->findBy(
                array('user' => $this->getUser()->getId()),
                array('date' => 'DESC'));

        return $this->render('flight/index.html.twig', array(
                    'flights' => $flights,
        ));
    }

    /**
     * Creates a new Flight entity.
     *
     * @Route("/new", name="flight_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $flight = new Flight();
        $flight->setUser($this->getUser());
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();

            return $this->redirectToRoute('flight_show', array('id' => $flight->getId()));
        }

        return $this->render('flight/new.html.twig', array(
                    'flight' => $flight,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Flight entity.
     *
     * @Route("/{id}", name="flight_show")
     * @Method("GET")
     */
    public function showAction(Flight $flight) {
        $deleteForm = $this->createDeleteForm($flight);

        return $this->render('flight/show.html.twig', array(
                    'flight' => $flight,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Flight entity.
     *
     * @Route("/{id}/edit", name="flight_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Flight $flight) {
        $deleteForm = $this->createDeleteForm($flight);
        $editForm = $this->createForm(FlightType::class, $flight);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();

            return $this->redirectToRoute('flight_show', array('id' => $flight->getId()));
        }

        return $this->render('flight/edit.html.twig', array(
                    'flight' => $flight,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Flight entity.
     *
     * @Route("/{id}", name="flight_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Flight $flight) {
        $form = $this->createDeleteForm($flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($flight);
            $em->flush();
        }

        return $this->redirectToRoute('flight_index');
    }

    /**
     * Creates a form to delete a Flight entity.
     *
     * @param Flight $flight The Flight entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Flight $flight) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('flight_delete', array('id' => $flight->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
