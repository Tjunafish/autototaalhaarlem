<?php

namespace Autoplan\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use VJP\WoningDossier\DBBundle\Entity\Dossier;

class CarSaleForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(  'required' => false,  'label' => 'Naam', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('email', 'text', array(  'required' => false,  'label' => 'E-mailadres', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')), new Assert\Email(array(
        	'message' => 'Ongeldig e-mailadres',
            'checkMX' => true
        )))));
        $builder->add('phone', 'text', array(  'required' => false,  'label' => 'Telefoonnummer', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')) )));
        $builder->add('license', 'text', array(  'required' => false,  'label' => 'Kenteken', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('message', 'textarea', array(  'required' => false,  'label' => 'Persoonlijk Bericht'));
    }

    public function getName()
    {
        return 'car_sale_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }

}