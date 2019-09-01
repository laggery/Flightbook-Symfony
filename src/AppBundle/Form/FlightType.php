<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FlightType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $user = $options['data']->getUser();

        $em = $options['entity_manager'];

        $dbGliders = $em->getRepository('AppBundle:Glider')->getGliderByUserId($user->getId());
        $gliderList = array();
        foreach($dbGliders as $k => $v) {
            $key = $v->getBrand() . " " . $v->getName();
            $gliderList[$key] = $v;
        }
        
        $dateRange = array();
        foreach(range(date('Y'), 1980) as $k => $v) {
            $dateRange[$v] = $v;
        }
        
        $builder->add('glider', ChoiceType::class, array(
                    'attr' => array('class' => 'selectfield'),
                    'required' => true,
                    'choices' => $gliderList,
                    'label' => 'flight.glider'
                ))
                ->add('date', DateType::class, array(
                    'format' => 'dd.MM.yyyy',
					'widget' => 'single_text',
					'html5' => false,
					'attr' => [
							'class' => 'js-datepicker',
							'autocomplete' => 'off'
						]))
                ->add('time', TimeType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'label' => 'flight.time',
                    'required' => false,
                    'attr' => array('class' => 'timeSelectfield')))
                ->add('startText', TextType::class, array(
                    'label' => 'flight.start',
                    'required' => false,
                ))
                ->add('landingText', TextType::class, array(
                    'label' => 'flight.landing',
                    'required' => false,
                ))
                ->add('price', null, array(
                    'label' => 'flight.price'))
                ->add('km', null, array(
                    'label' => 'flight.km'))
                ->add('description', TextareaType::class, array(
                    'required' => false,
                    'label' => 'flight.description'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Flight'
        ));

        $resolver->setRequired('entity_manager');
    }

}
