<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Name', 'attr' => array('class' => 'span8')))
            ->add('subscribers', null, array('label' => 'Subscribers', 'required' => false))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\NewsletterGroup'
        ));
    }

    public function getName()
    {
        return 'autoplan_newsletter_group';
    }
}
