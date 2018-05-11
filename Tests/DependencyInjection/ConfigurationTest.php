<?php

namespace Tests\Chaplean\Bundle\SorClientBundle\DependencyInjection;

use Chaplean\Bundle\UserBundle\DependencyInjection\ChapleanUserExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tests\Chaplean\Bundle\UserBundle\Entity\DummyUser;

/**
 * Class ConfigurationTest.
 *
 * @package   Tests\Chaplean\Bundle\SorClientBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ConfigurationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\UserBundle\DependencyInjection\Configuration::getConfigTreeBuilder()
     *
     * @return void
     */
    public function testFullyDefinedConfig()
    {
        $container = new ContainerBuilder();
        $loader = new ChapleanUserExtension();
        $loader->load(
            [
                [
                    'entity'         => [
                        'user' => [
                            'class' => DummyUser::class,
                        ]
                    ],
                    'template_login' => '',
                    'controller'     => [
                        'index_route'        => '',
                        'login_route'        => '',
                        'set_password_route' => '',
                    ],
                    'emailing'       => [
                        'register'  => [
                            'subject' => '',
                            'body'    => '',
                        ],
                        'resetting' => [
                            'subject' => '',
                            'body'    => '',
                        ]
                    ]
                ]
            ],
            $container
        );

        $this->assertTrue($container->hasParameter('chaplean_user.entity.user.class'));
        $this->assertTrue($container->hasParameter('chaplean_user.template_login'));
        $this->assertTrue($container->hasParameter('chaplean_user.controller.index_route'));
        $this->assertTrue($container->hasParameter('chaplean_user.controller.login_route'));
        $this->assertTrue($container->hasParameter('chaplean_user.controller.set_password_route'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.register.subject'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.register.body'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.resetting.subject'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.resetting.body'));
    }

    /**
     * @covers \Chaplean\Bundle\UserBundle\DependencyInjection\Configuration::getConfigTreeBuilder()
     *
     * @return void
     */
    public function testDefaultConfig()
    {
        $container = new ContainerBuilder();
        $loader = new ChapleanUserExtension();
        $loader->load([[]], $container);

        $this->assertFalse($container->hasParameter('chaplean_user.entity.user.class'));
        $this->assertTrue($container->hasParameter('chaplean_user.template_login'));
        $this->assertFalse($container->hasParameter('chaplean_user.controller.index_route'));
        $this->assertFalse($container->hasParameter('chaplean_user.controller.login_route'));
        $this->assertFalse($container->hasParameter('chaplean_user.controller.set_password_route'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.register.subject'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.register.body'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.resetting.subject'));
        $this->assertTrue($container->hasParameter('chaplean_user.emailing.resetting.body'));
    }
}
