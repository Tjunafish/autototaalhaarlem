<?php

namespace Autoplan\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Name', 'attr' => array('class' => 'span8')))
            ->add('email', null, array('label' => 'Email', 'attr' => array('class' => 'span8')))
            ->add('active', 'checkbox', array(
                'label' => 'Active',
                'required'  => false
            ))
            ->add('groups', null, array(
                'label' => 'Groups',
                'required'  => false
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Autoplan\DBBundle\Entity\NewsletterSubscription'
        ));
    }

    public function getName()
    {
        return 'autoplan_newsletter_subscription';
    }
}
