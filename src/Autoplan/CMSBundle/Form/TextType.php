<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textKey', null, array('label' => 'Sleutel', 'attr' => array('class' => 'span8')))
            ->add('title', null, array('label' => 'Titel', 'attr' => array('class' => 'span8')))
            ->add('content', null, array('label' => 'Body', 'attr' => array('class' => 'wysihtml5', 'style' => 'height:300px')))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\Text'
        ));
    }

    public function getName()
    {
        return 'autoplan_text';
    }
}
