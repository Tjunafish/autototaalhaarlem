<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Naam', 'attr' => array('class' => 'span8')))
            ->add('position', null, array('label' => 'Functie', 'attr' => array('class' => 'span8')))
            ->add('tagline', null, array('label' => 'Tagline', 'attr' => array('class' => 'span8')))
            ->add('image', 'autoplan_image_upload', array('required' => false, 'data_class' => 'Symfony\\Component\\HttpFoundation\\File\\File',  'label' => 'Afbeelding', 'previewfilter' => 'employee_image'))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\Employee'
        ));
    }

    public function getName()
    {
        return 'autoplan_employee';
    }
}
