<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FAQType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', null, array('required' => false, 'label' => 'Vraag', 'attr' => array('class' => 'span8')))
            ->add('answer', null, array('label' => 'Antwoord', 'attr' => array('class' => 'span8')))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\FAQ'
        ));
    }

    public function getName()
    {
        return 'autoplan_faq';
    }
}
