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
        $builder->add('date', DateType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'format' => 'dd.MM.yyyy',
					'attr' => array('class' => 'dateSelectfield')))
                ->add('time', TimeType::class, array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'required' => false,
					'attr' => array('class' => 'timeSelectfield')))
                ->add('description')
                ->add('price')
                ->add('km')
                ->add('user', null, array(
    'attr' => array('class' => 'selectfield')))
                ->add('landing', null, array(
    'attr' => array('class' => 'selectfield')))
                ->add('start', null, array(
    'attr' => array('class' => 'selectfield')))
                ->add('glider', null, array(
    'attr' => array('class' => 'selectfield')));
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
