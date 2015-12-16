<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationFormType.
 *
 * @package   Chaplean\Bundle\UserBundle\Form\Type
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'translation_domain' => 'validators'
            ))
            ->add('dateAdd', DateTimeType::class, array(
                'input' => 'datetime',
                'data' => new \DateTime('now'),
            ));
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Chaplean\Bundle\UserBundle\Doctrine\User',
                'validation_groups' => array('registration'),
                'translation_domain' => 'messages'
            )
        );
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'chaplean_user_registration';
    }
}
