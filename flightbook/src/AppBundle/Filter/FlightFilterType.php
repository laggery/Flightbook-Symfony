<?php

namespace AppBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

class FlightFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('date', DateRangeFilterType::class, array(
                    'label' => 'flight.from'
                ))
                ->add('brand', TextFilterType::class, array(
                    'label' => 'glider.brand',
                    'apply_filter' => function(QueryInterface $filterQuery, $field, $values) {
                        if (!empty($values['value'])) {
                            $expression = $filterQuery->getExpr()->like('g.brand', "'" . $values['value'] . "'");
                            return $filterQuery->createCondition($expression);
                        }
                    }
                ))
                ->add('name', TextFilterType::class, array(
                    'label' => 'glider.name',
                    'apply_filter' => function(QueryInterface $filterQuery, $field, $values) {
                        if (!empty($values['value'])) {
                            $expression = $filterQuery->getExpr()->like('g.name', "'" . $values['value'] . "'");
                            return $filterQuery->createCondition($expression);
                        }
                    }
                ))
                ->add('tandem', ChoiceFilterType::class, array(
                    'label' => 'glider.type',
                    'choices' => array('Solo' => 2, 'Tandem' => 1),
                    'attr' => array('class' => 'selectfield'),
                    'apply_filter' => function(QueryInterface $filterQuery, $field, $values) {
                        if (!empty($values['value'])) {
                            $values['value'] == 2 ? $val = 0 : $val = 1;
                            $expression = $filterQuery->getExpr()->eq('g.tandem', $val);
                            return $filterQuery->createCondition($expression);
                        }
                    }
                ))
                ->add('reset', ResetType::class, array(
                    'label' => 'buttons.clear',
                    'attr' => array('class' => 'clear btn-danger'),
        ));
    }

    public function getName() {
        return 'flight_filter';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

}
