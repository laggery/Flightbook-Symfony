<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Glider;
use AppBundle\Form\GliderType;

/**
 * Glider controller.
 *
 * @Route("/glider")
 */
class GliderController extends Controller
{
    /**
     * Lists all Glider entities.
     *
     * @Route("/", name="glider_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gliders = $em->getRepository('AppBundle:Glider')->findBy(
                array('user' => $this->getUser()->getId()),
                array('brand' => 'ASC'));

        return $this->render('glider/index.html.twig', array(
            'gliders' => $gliders,
        ));
    }

    /**
     * Creates a new Glider entity.
     *
     * @Route("/new", name="glider_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $glider = new Glider();
        $glider->setUser($this->getUser());
        $form = $this->createForm(GliderType::class, $glider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($glider);
            $em->flush();

            return $this->redirectToRoute('glider_show', array('id' => $glider->getId()));
        }

        return $this->render('glider/new.html.twig', array(
            'glider' => $glider,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Glider entity.
     *
     * @Route("/{id}", name="glider_show")
     * @Method("GET")
     */
    public function showAction(Glider $glider)
    {
        $deleteForm = $this->createDeleteForm($glider);

        return $this->render('glider/show.html.twig', array(
            'glider' => $glider,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Glider entity.
     *
     * @Route("/{id}/edit", name="glider_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Glider $glider)
    {
        $deleteForm = $this->createDeleteForm($glider);
        $editForm = $this->createForm(GliderType::class, $glider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($glider);
            $em->flush();

            return $this->redirectToRoute('glider_show', array('id' => $glider->getId()));
        }

        return $this->render('glider/edit.html.twig', array(
            'glider' => $glider,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Glider entity.
     *
     * @Route("/{id}", name="glider_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Glider $glider)
    {
        $form = $this->createDeleteForm($glider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($glider);
            $em->flush();
        }

        return $this->redirectToRoute('glider_index');
    }

    /**
     * Creates a form to delete a Glider entity.
     *
     * @param Glider $glider The Glider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Glider $glider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('glider_delete', array('id' => $glider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
