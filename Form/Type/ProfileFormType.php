<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use FOS\UserBundle\Tests\Form\Type\RegistrationFormTypeTest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileFormType
 *
 * @package   Chaplean\Bundle\UserBundle\Form\Type
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
 * @since     0.1.0
 */
class ProfileFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
            $builder
                ->remove('dateAdd')
                ->add('dateUpdate', DateTimeType::class, array(
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
                'validation_groups' => array('profile'),
                'translation_domain' => 'messages'
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return RegistrationFormType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'chaplean_user_profile';
    }
}
