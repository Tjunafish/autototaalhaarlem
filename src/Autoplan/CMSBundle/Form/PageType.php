<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titel', 'attr' => array('class' => 'span8')))
            ->add('content', null, array('label' => 'Body', 'attr' => array('class' => 'wysihtml5', 'style' => 'height:500px')))
            ->add("showrightblock", null, array('label' => 'Toon rechterblok', 'required' => false))
            ->add('seoTitle', null, array('label' => 'SEO Titel', 'attr' => array('class' => 'span8')))
            ->add('seoDescription', null, array('label' => 'SEO omschrijving', 'attr' => array('class' => 'span8')))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\Page'
        ));
    }

    public function getName()
    {
        return 'autoplan_page';
    }
}
