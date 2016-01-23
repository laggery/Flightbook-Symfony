<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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
        $builder->add('glider', EntityType::class, array(
                    'class' => 'AppBundle:Glider',
                    'attr' => array('class' => 'selectfield'),
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('g')
                                ->where('g.user =' . $user->getId());
                    },
                    'required' => true,
                    'placeholder' => '',
                    'empty_data'  => null
                ))
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
                ->add('start', EntityType::class, array(
                    'class' => 'AppBundle:Place',
                    'attr' => array('class' => 'selectfield'),
                    'query_builder' => $placesFunction,
                    'required' => false,
                ))
                ->add('landing', EntityType::class, array(
                    'class' => 'AppBundle:Place',
                    'attr' => array('class' => 'selectfield'),
                    'query_builder' => $placesFunction,
                    'required' => false,
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
