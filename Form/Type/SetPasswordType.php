<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Model\SetPasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SetPasswordType.
 *
 * @package   Chaplean\Bundle\UserBundle\Form\Type
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coopn.coop)
 * @since     4.0.0
 */
class SetPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type'            => PasswordType::class,
                'first_name'      => 'first',
                'second_name'     => 'second',
                'invalid_message' => 'fos_user.password.mismatch',
                'required'        => true
            ]
        );

        $builder->add(
            'token',
            HiddenType::class,
            [
                'required' => true
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => SetPasswordModel::class,
                'translation_domain' => 'messages',
                'csrf_protection'    => true,
                'csrf_token_id'      => 'chaplean_user_set_password'
            ]
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'chaplean_user_set_password';
    }
}
