<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Doctrine\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                ->add('dateUpdate', 'datetime', array(
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
        return 'chaplean_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chaplean_user_profile';
    }
}
