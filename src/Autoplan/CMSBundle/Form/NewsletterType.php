<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Title', 'attr' => array('class' => 'span8')))
            ->add('subject', null, array('label' => 'Subject', 'attr' => array('class' => 'span8')))
            ->add('text', null, array('label' => 'Text (optional)', 'required' => false, 'attr' => array('class' => 'wysihtml5', 'style' => 'height:300px')))
            ->add('cars', null, array('label' => 'Cars', 'required' => false))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\Newsletter'
        ));
    }

    public function getName()
    {
        return 'autoplan_newsletter';
    }
}
