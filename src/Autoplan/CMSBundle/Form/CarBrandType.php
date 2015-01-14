<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarBrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, array('label' => 'Omschrijving', 'attr' => array('class' => 'wysihtml5', 'style' => 'height:300px')))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\CarBrand'
        ));
    }

    public function getName()
    {
        return 'autoplan_car_brand';
    }
}
