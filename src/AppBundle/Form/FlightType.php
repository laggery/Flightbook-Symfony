<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FlightType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $user = $options['data']->getUser();
        $placesFunction = function (EntityRepository $er) use ($user) {
            return $er->createQueryBuilder('p')
                            ->where('p.user =' . $user->getId());
        };
        
        $dateRange = array();
        foreach(range(date('Y'), 1980) as $k => $v) {
            $dateRange[$v] = $v;
        }
        
        $builder->add('glider', EntityType::class, array(
                    'class' => 'AppBundle:Glider',
                    'attr' => array('class' => 'selectfield'),
                    'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('g')
                        ->where('g.user =' . $user->getId());
            },
                    'required' => true,
                    'placeholder' => '',
                    'label' => 'flight.glider',
                    'empty_data' => null
                ))
                ->add('date', DateType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'format' => 'dd.MM.yyyy',
                    'label' => 'flight.date',
                    'years' => $dateRange,
                    'attr' => array('class' => 'dateSelectfield')))
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
//                ->add('start', EntityType::class, array(
//                    'class' => 'AppBundle:Place',
//                    'attr' => array('class' => 'selectfield'),
//                    'query_builder' => $placesFunction,
//                    'label' => 'flight.start',
//                    'required' => false,
//                ))
//                ->add('landing', EntityType::class, array(
//                    'class' => 'AppBundle:Place',
//                    'attr' => array('class' => 'selectfield'),
//                    'query_builder' => $placesFunction,
//                    'label' => 'flight.landing',
//                    'required' => false,
//                ))
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
    }

}
