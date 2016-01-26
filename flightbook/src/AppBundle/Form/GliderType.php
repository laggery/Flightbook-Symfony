<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GliderType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('buyDate', DateType::class, array(
                    'input' => 'datetime',
                    'required' => false,
                    'widget' => 'choice',
                    'format' => 'dd.MM.yyyy',
                    'label' => 'glider.buydate',
                    'attr' => array('class' => 'dateSelectfield')))
                ->add('brand', null, array(
                    'label' => 'glider.brand'))
                ->add('name', null, array(
                    'label' => 'glider.name'))
                ->add('tandem', null, array(
                    'label' => 'glider.tandem'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Glider'
        ));
    }

}
