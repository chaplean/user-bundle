<?php

namespace Chaplean\Bundle\UserBundle\Form\Type;

use Chaplean\Bundle\UserBundle\Entity\User;
use Symfony\Component\CssSelector\XPath\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
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
        parent::buildForm($builder, $options);
        $builder
            ->remove('username')
            ->remove('plainPassword')
            ->add(
                'title',
                'choice',
                array(
                    'choices'  => User::getEnumTitle(),
                    'required' => true,
                    'expanded' => true
                )
            )
            ->add('firstname', 'text')
            ->add('lastname', 'text')
            ->add('email', 'email', array(
                'translation_domain' => 'validators'
            ))
            ->add('companySize', 'choice', array(
                'empty_value' => 'register.builder.company_size.empty',
                'choices'  => array(
                    '0'   => 'register.builder.company_size.choice.not_applicable',
                    '1'   => 'register.builder.company_size.choice.one_to',
                    '10'  => 'register.builder.company_size.choice.ten_to',
                    '50'  => 'register.builder.company_size.choice.fifty_to',
                    '250' => 'register.builder.company_size.choice.250_to',
                    '5000' => 'register.builder.company_size.choice.more',
                ),
                'required' => true,
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
                'data_class' => 'Chaplean\Bundle\UserBundle\Entity\User',
                'validation_groups' => array('registration'),
                'translation_domain' => 'messages'
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chaplean_user_registration';
    }
}
