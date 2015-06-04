<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('email', 'email', array(
                'translation_domain' => 'validators'
            ))
            ->add('dateAdd', 'datetime', array(
                'input' => 'datetime',
                'data' => new \DateTime('now'),
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
    public function getName()
    {
        return 'chaplean_user_registration';
    }
}
