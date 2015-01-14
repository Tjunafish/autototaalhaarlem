<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titel (niet zichtbaar op website)', 'attr' => array('class' => 'span8')))
            ->add('url', null, array('label' => 'URL', 'attr' => array('class' => 'span8')))
            ->add('image', 'autoplan_image_upload', array('required' => false, 'data_class' => 'Symfony\\Component\\HttpFoundation\\File\\File',  'label' => 'Afbeelding', 'previewfilter' => 'employee_image'))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\Banner'
        ));
    }

    public function getName()
    {
        return 'ash_banner';
    }
}
