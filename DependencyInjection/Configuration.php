<?php

namespace Chaplean\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @package   Chaplean\Bundle\UserBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('chaplean_user');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('entity')
                    ->children()
                        ->arrayNode('user')
                            ->children()
                                ->scalarNode('class')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('template_login')->defaultValue('')->end()
                ->arrayNode('controller')
                    ->children()
                        ->scalarNode('index_route')->isRequired()->end()
                        ->scalarNode('login_route')->isRequired()->end()
                        ->scalarNode('set_password_route')->defaultValue('chaplean_user_password_set_password')->end()
                    ->end()
                ->end()
                ->arrayNode('emailing')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('register')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('subject')->defaultValue('register.mail.subject')->end()
                                ->scalarNode('body')->defaultValue('ChapleanUserBundle:Email:register-password.txt.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('resetting')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('subject')->defaultValue('forgot.mail.subject')->end()
                                ->scalarNode('body')->defaultValue('ChapleanUserBundle:Email:resetting-password.txt.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
