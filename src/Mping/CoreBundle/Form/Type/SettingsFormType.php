<?php
namespace Mping\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('licence', 'text', array('label' => 'Numéro de licence: ', 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mping\CoreBundle\Entity\User',
            'validation_groups' => 'settings',
        ));
    }

    public function getName()
    {
        return 'user_settings';
    }
}