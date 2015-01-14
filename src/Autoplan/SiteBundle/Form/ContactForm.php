<?php

namespace Autoplan\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use VJP\WoningDossier\DBBundle\Entity\Dossier;

class ContactForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(  'required' => false,  'label' => 'Naam', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('phone', 'text', array(  'required' => false,  'label' => 'Telefoonnummer', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')) )));
    }

    public function getName()
    {
        return 'contact_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }

}