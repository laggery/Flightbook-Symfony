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
        $dateRange = array();
        foreach(range(date('Y'), 1980) as $k => $v) {
            $dateRange[$v] = $v;
        }
        
        $builder->add('buyDate', DateType::class, array(
                    'format' => 'dd.MM.yyyy',
					'widget' => 'single_text',
					'html5' => false,
					'attr' => [
							'class' => 'js-datepicker',
							'autocomplete' => 'off'
						]))
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
