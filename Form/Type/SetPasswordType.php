<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Validator\Constraints\MinimalPasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SetPasswordType.
 *
 * @package   Chaplean\Bundle\UserBundle\Form\Type
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
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
            array(
                'type'            => 'password',
                'first_name'      => 'first',
                'second_name'     => 'second',
                'invalid_message' => 'fos_user.password.mismatch',
                'required'        => true
            )
        );
        $builder->add(
            'token',
            HiddenType::class,
            array(
                'required' => true
            )
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
            array(
                'data_class' => 'Chaplean\Bundle\UserBundle\Model\SetPasswordModel',
                'translation_domain' => 'messages',
                'csrf_protection'    => false,
            )
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
