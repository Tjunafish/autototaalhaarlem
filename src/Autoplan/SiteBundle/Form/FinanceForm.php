<?php

namespace Autoplan\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use VJP\WoningDossier\DBBundle\Entity\Dossier;

class FinanceForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //placeholders komen later
        $builder->add('car', 'text', array( 'attr' => array('placeholder' => 'auto'), 'required' => false,  'label' => 'Auto', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('finance', 'text', array(  'required' => false,  'label' => 'Auto', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('name', 'text', array(  'required' => false,  'label' => 'Naam', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
         $builder->add('phone', 'text', array(  'required' => false,  'label' => 'Telefoonnummer', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')) )));
        $builder->add('email', 'text', array(  'required' => false,  'label' => 'E-mailadres', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')), new Assert\Email(array(
        	'message' => 'Ongeldig e-mailadres',
            'checkMX' => true
        )))));
        $builder->add('netto', 'text', array(  'required' => false,  'label' => 'Netto inkomen', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('nettopartner', 'text', array(  'required' => false,  'label' => 'Netto partner', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('rent', 'text', array(  'required' => false,  'label' => 'Huur', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('license', 'text', array(  'required' => false,  'label' => 'Kenteken', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
    }

    public function getName()
    {
        return 'finance_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            
        ));
    }

}
