<?php

namespace Chaplean\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @package   Chaplean\Bundle\UserBundle\DependencyInjection
 * @author    Valentin - Chaplean <valentin@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
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
                ->scalarNode('template_login')->defaultValue('')->end()
                ->arrayNode('controller')
                    ->children()
                        ->scalarNode('index_route')->isRequired()->end()
                        ->scalarNode('login_route')->isRequired()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
