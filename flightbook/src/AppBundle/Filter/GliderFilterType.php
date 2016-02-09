<?php

namespace AppBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class GliderFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('brand', TextFilterType::class, array(
                    'label' => 'glider.brand'
                ))
                ->add('name', TextFilterType::class, array(
                    'label' => 'glider.name'
                ))
                ->add('tandem', ChoiceFilterType::class, array(
                    'choices' => array('glider.solo' => 0, 'glider.tandem' => 1),
                    'label' => 'glider.type',
                    'attr' => array('class' => 'selectfield')
                ))
                ->add('reset', ResetType::class, array(
                    'label' => 'buttons.clear',
                    'attr' => array('class' => 'clear btn-danger'),
        ));
    }

    public function getName() {
        return 'glider_filter';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

}
