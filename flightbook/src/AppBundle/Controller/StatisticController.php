<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Filter\FlightFilterType;
use Symfony\Component\HttpFoundation\Request;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\VAxis;

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
        $filterBuilder->innerJoin('f.glider', 'g')->where("f.user =" . $this->getUser()->getId())->orderBy('f.date', 'asc');

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
            if (isset($barChartVal[$flight->getDate()->format('Y')])) {
                $incomeYear = $barChartVal[$flight->getDate()->format('Y')][1] + $flight->getPrice();
                $nbYear = $barChartVal[$flight->getDate()->format('Y')][0] + 1;
                $barChartVal[$flight->getDate()->format('Y')] = [$nbYear, $incomeYear];

                $minutesYear = $lineChartVal[$flight->getDate()->format('Y')][0] + $this->getMinutes($flight->getTime()->format('H:i:s'));
                $lineChartVal[$flight->getDate()->format('Y')] = [$minutesYear, $nbYear];
            } else {
                $barChartVal[$flight->getDate()->format('Y')] = [1, $flight->getPrice()];
                $lineChartVal[$flight->getDate()->format('Y')] = [$this->getMinutes($flight->getTime()->format('H:i:s')), 1];
            }
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

        $lineChart = $this->lineChart($lineChartVal);
        $barChart = $this->barChart($barChartVal);

        return $this->render('statistic/index.html.twig', array(
                    'nbFlight' => $nbFlight,
                    'income' => $income,
                    'totalTime' => $total,
                    'average' => $average,
                    'form' => $form->createView(),
                    'linechart' => $lineChart,
                    'barchart' => $barChart,
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

    private function getMinutes($time) {
        $minutes = 0;
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;

        return $minutes;
    }

    private function lineChart($table) {
        $data[] = ['Year', $this->get('translator')->trans('statistic.flighthour', array(), 'messages'), $this->get('translator')->trans('statistic.averageMin', array(), 'messages')];

        foreach ($table as $key => $value) {
            $hour = $value[0] / 60;
            $average = ($value[0] / $value[1]);
            $data[] = ['' . $key . '', round($hour), round($average)];
        }

        $chart = new ComboChart();
        $chart->getData()->setArrayToDataTable($data);
//        $chart->getOptions()->setHeight(600);
//        $chart->getOptions()->setWidth(900);
        $chart->getOptions()->getTooltip()->getTextStyle()->setBold(true);

        $vAxis1 = new VAxis();
        $vAxis1->setTitle($this->get('translator')->trans('statistic.hours', array(), 'messages'));
        $vAxis1->setMinValue(0);
        $vAxis2 = new VAxis();
        $vAxis2->setTitle($this->get('translator')->trans('statistic.minutes', array(), 'messages'));
        $vAxis2->setMinValue(0);
        $chart->getOptions()->setVAxes([$vAxis1, $vAxis2]);

        /* Income */
        $series1 = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series1->setType('line');
        $series1->setTargetAxisIndex(0);
        $series1->setColor('#00ff00');

        /* Evolution */
        $series2 = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series2->setType('line');
        $series2->setTargetAxisIndex(1);
        $series2->setColor('#f6dc12');

        $chart->getOptions()->setSeries([$series1, $series2]);

        $chart->getOptions()->getHAxis()->setTitle($this->get('translator')->trans('statistic.years', array(), 'messages'));
        return $chart;
    }

    private function barChart($table) {
        $data[] = [$this->get('translator')->trans('statistic.years', array(), 'messages'), $this->get('translator')->trans('statistic.nbflight', array(), 'messages'), $this->get('translator')->trans('statistic.price', array(), 'messages')];

        foreach ($table as $key => $value) {
            $data[] = ['' . $key . '', $value[0], $value[1]];
        }

        $chart = new ComboChart();
        $chart->getData()->setArrayToDataTable($data);
//        $chart->getOptions()->setHeight(600);
//        $chart->getOptions()->setWidth(900);
        $chart->getOptions()->getTooltip()->getTextStyle()->setBold(true);

        $vAxis1 = new VAxis();
        $vAxis1->setTitle($this->get('translator')->trans('flights', array(), 'messages'));
        $vAxis1->setMinValue(0);
        $vAxis2 = new VAxis();
        $vAxis2->setTitle($this->get('translator')->trans('statistic.francs', array(), 'messages'));
        $vAxis2->setMinValue(0);
        $chart->getOptions()->setVAxes([$vAxis1, $vAxis2]);

        /* Income */
        $series1 = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series1->setType('bars');
        $series1->setTargetAxisIndex(0);
        $series1->setColor('#00ff00');

        /* Evolution */
        $series2 = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series2->setType('bars');
        $series2->setTargetAxisIndex(1);
        $series2->setColor('#f6dc12');

        $chart->getOptions()->setSeries([$series1, $series2]);

        $chart->getOptions()->getHAxis()->setTitle($this->get('translator')->trans('statistic.years', array(), 'messages'));
        return $chart;
    }

}
