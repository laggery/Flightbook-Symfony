<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Flight;
use AppBundle\Entity\Place;
use AppBundle\Form\FlightType;
use AppBundle\Filter\FlightFilterType;

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
    public function indexAction(Request $request) {
        $filter = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "?"));
        $hasFilter = strspn($filter,"?");
        if ($hasFilter==0){
            $filter = null;
        }
        $form = $this->createForm(FlightFilterType::class);
        
        $em = $this->getDoctrine()->getManager();
        $filterBuilder = $em->getRepository('AppBundle:Flight')->createQueryBuilder('f');
        $filterBuilder->innerJoin('f.glider', 'g')->where("f.user =" . $this->getUser()->getId())->add('orderBy','f.date DESC, f.timestamp DESC');
        
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
            
//            var_dump($filterBuilder->getDql()); die;
        }
        
        $flights = $filterBuilder->getQuery()->execute();

        return $this->render('flight/index.html.twig', array(
                    'flights' => $flights,
                    'form' => $form->createView(),
                    'filter' => $filter
        ));
    }
    
    /**
     * Export flights
     * 
     * @Route("/export", name="flight_export")
     * @Method("GET")
     */
    public function exportAction(Request $request) {
        $form = $this->createForm(FlightFilterType::class);
        
        $em = $this->getDoctrine()->getManager();
        $filterBuilder = $em->getRepository('AppBundle:Flight')->createQueryBuilder('f');
        $filterBuilder->innerJoin('f.glider', 'g')->where("f.user =" . $this->getUser()->getId())->orderBy('f.date', 'desc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }
        
        $flights = $filterBuilder->getQuery()->execute();

        $filename = "export_".date("Y_m_d_His").".csv";
        $response = $this->render('flight/exportCSV.html.twig', array(
                    'flights' => $flights
        ));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename); 
        return $response; 
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/exportXLS.xlsx", name="flight_exportXLS", defaults={"templateName" = "exportXLS", "_format" = "xlsx"})
     */
    public function exportXLSAction(Request $request) {
        $filter = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "?"));
        $hasFilter = strspn($filter,"?");
        if ($hasFilter==0){
            $filter = null;
        }
        $form = $this->createForm(FlightFilterType::class);
        
        $em = $this->getDoctrine()->getManager();
        $filterBuilder = $em->getRepository('AppBundle:Flight')->createQueryBuilder('f');
        $filterBuilder->innerJoin('f.glider', 'g')->where("f.user =" . $this->getUser()->getId())->orderBy('f.date', 'desc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }
        
        $flights = $filterBuilder->getQuery()->execute();
        
        return $this->render('flight/exportXLS.xlsx.twig', array(
                    'flights' => $flights
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
        $em = $this->getDoctrine()->getManager();
        $flight->setUser($this->getUser());
        $lastFlight = $em->getRepository('AppBundle:Flight')->getLastFlight($this->getUser()->getId());
        if ($lastFlight) {
            $flight->setGlider($lastFlight->getGlider());
        } else {
            $gliderList = $em->getRepository('AppBundle:Glider')->getGliderByUserId($this->getUser()->getId());
            if (count($gliderList) == 0) {
                return $this->redirectToRoute('glider_new', array('id' => $flight->getId()));
            }
        }

        $form = $this->createForm(FlightType::class, $flight, [
            'entity_manager' => $this->getDoctrine()->getManager(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flight = $this->savePlace($flight);
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
        if ($flight->getStart()) {
            $flight->setStartText($flight->getStart()->getName());
        }
        if ($flight->getLanding()) {
            $flight->setLandingText($flight->getLanding()->getName());
        }
        $editForm = $this->createForm(FlightType::class, $flight, [
            'entity_manager' => $this->getDoctrine()->getManager(),
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $flight = $this->savePlace($flight);
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
     * Displays a form to edit an existing Flight entity.
     *
     * @Route("/{id}/copy", name="flight_copy")
     * @Method({"GET", "POST"})
     */
    public function copyAction(Request $request, Flight $flight) {
        $copy = clone $flight;
        $em = $this->getDoctrine()->getManager();
        $em->persist($copy);
        $em->flush();

        $this->addFlash('notice', $this->get('translator')->trans('message.copyFlight'));

        return $this->redirectToRoute('flight_show', array('id' => $copy->getId()));
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

    private function savePlace(Flight $flight) {
        $em = $this->getDoctrine()->getManager();
        if ($flight->getStartText() != '') {
            $place = $em->getRepository('AppBundle:Place')->findSingleResultByName($this->getUser()->getId(), $flight->getStartText());
            if ($place) {
                $flight->setStart($place);
            } else {
                $place = new Place();
                $place->setName($flight->getStartText());
                $place->setUser($flight->getUser());
                $em->persist($place);
                $em->flush();
                $flight->setStart($place);
            }
        } else {
            $flight->setStart(null);
        }
        if ($flight->getLandingText() != '') {
            $place = $em->getRepository('AppBundle:Place')->findSingleResultByName($this->getUser()->getId(), $flight->getLandingText());
            if ($place) {
                $flight->setLanding($place);
            } else {
                $place = new Place();
                $place->setName($flight->getLandingText());
                $place->setUser($flight->getUser());
                $em->persist($place);
                $em->flush();
                $flight->setLanding($place);
            }
        } else {
            $flight->setLanding(null);
        }

        return $flight;
    }

}
