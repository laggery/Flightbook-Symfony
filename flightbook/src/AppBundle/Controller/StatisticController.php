<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Filter\FlightFilterType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Statistic controller.
 *
 * @Route("/statistic")
 */
class StatisticController extends Controller {

    /**
     * Lists of statistics.
     *
     * @Route("/", name="statistic_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $form = $this->createForm(FlightFilterType::class);

        $em = $this->getDoctrine()->getManager();
        $filterBuilder = $em->getRepository('AppBundle:Flight')->createQueryBuilder('f');
        $filterBuilder->innerJoin('f.glider', 'g')->where("f.user =" . $this->getUser()->getId())->orderBy('f.date', 'desc');

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }

        $flights = $filterBuilder->getQuery()->execute();

        $nbFlight = 0;
        $income = 0;
        $total = '00:00:00';
        $times = array();
        foreach ($flights as $flight) {
            $nbFlight++;
            $income = $income + $flight->getPrice();
            if ($flight->getTime()) {
                array_push($times, $flight->getTime()->format('H:i:s'));
                $total = $this->sum_the_time($flight->getTime()->format('H:i:s'), $total);
            } else {
                array_push($times, '00:00:00');
            }
        }

        // average to verifie seems to be false
        if ($nbFlight > 0) {
            $average = date('H:i:s', array_sum(array_map('strtotime', $times)) / $nbFlight);
        } else {
            $average = 0;
        }

        return $this->render('statistic/index.html.twig', array(
                    'nbFlight' => $nbFlight,
                    'income' => $income,
                    'totalTime' => $total,
                    'average' => $average,
                    'form' => $form->createView(),
        ));
    }

    private function sum_the_time($time1, $time2) {
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time) {
            list($hour, $minute, $second) = explode(':', $time);
            $seconds += $hour * 3600;
            $seconds += $minute * 60;
            $seconds += $second;
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // 
    }

}
