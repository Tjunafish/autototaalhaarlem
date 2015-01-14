<?php
/**
 * Created by PhpStorm.
 * User: simonsabelis
 * Date: 11/16/13
 * Time: 11:45 AM
 */

namespace Autoplan\CMSBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ImageUploadType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));

        $resolver->setRequired(array('previewfilter'));
    }

    /**
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $image = null;

        $parentData = $form->getParent()->getData();
        if (null !== $parentData) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $image = $accessor->getValue($parentData, $form->getName());
        }
        $view->vars['imageProp'] = $form->getName();
        $view->vars['object'] = $form->getParent()->getData();
        $view->vars['previewfilter'] = $options['previewfilter'];
        $view->vars['hasImage'] = null!==$image;
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'autoplan_image_upload';
    }
}