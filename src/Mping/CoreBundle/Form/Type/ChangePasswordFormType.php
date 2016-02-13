<?php
namespace Mping\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', 'password', array(
            'label' => 'Mot de passe actuel',
            
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'first_options' => array('label' => 'Nouveau mot de passe'),
            'second_options' => array('label' => 'Confirmation'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mping\CoreBundle\Entity\User',
            'validation_groups' => 'changePassword',
        ));
    }

    public function getName()
    {
        return 'user_change_password';
    }
}