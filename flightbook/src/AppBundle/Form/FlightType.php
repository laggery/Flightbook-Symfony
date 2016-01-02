<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('glider', null, array(
                    'attr' => array('class' => 'selectfield')))
                ->add('date', DateType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'format' => 'dd.MM.yyyy',
                    'attr' => array('class' => 'dateSelectfield')))
                ->add('time', TimeType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'required' => false,
                    'attr' => array('class' => 'timeSelectfield')))
                ->add('start', null, array(
                    'attr' => array('class' => 'selectfield')))
                ->add('landing', null, array(
                    'class' => 'AppBundle:Place',
                    'attr' => array('class' => 'selectfield'),
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('p')
//                                ->where('p.user = 1');
//                    }
                ))
                ->add('price')
                ->add('km')
                ->add('description');                
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
