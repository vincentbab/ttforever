<?php
namespace Mping\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('username', null, array('label' => 'Nom d\'utilisateur'))
            ->add('email', 'email', array('label' => 'Email: '))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Mot de passe: '),
                'second_options' => array('label' => 'Confirmation: '),
                'invalid_message' => 'Le mot de passe de correspond pas',
            ))
            ->add('licence', 'text', array('label' => 'Numéro de licence: ', 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mping\CoreBundle\Entity\User',
            'validation_groups' => 'registration',
        ));
    }

    public function getName()
    {
        return 'user_registration';
    }
}