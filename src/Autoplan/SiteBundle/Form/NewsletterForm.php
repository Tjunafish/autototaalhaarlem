<?php

namespace Autoplan\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use VJP\WoningDossier\DBBundle\Entity\Dossier;

class NewsletterForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(  'required' => false,  'label' => 'Naam', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')))));
        $builder->add('email', 'text', array(  'required' => false,  'label' => 'E-mailadres', 'constraints' => array(new NotBlank(array('message' => 'Verplicht veld')), new Assert\Email(array(
            'message' => 'Ongeldig e-mailadres',
            'checkMX' => true
        )))));
    }

    public function getName()
    {
        return 'newsletter_form';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));
    }

}
