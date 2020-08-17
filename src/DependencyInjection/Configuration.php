<?php

declare(strict_types=1);

namespace Tbajorek\DoctrineFileFixturesBundle\DependencyInjection;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_NAME = 'doctrine_file_fixtures';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder(self::ROOT_NAME, 'array');
            $root = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder(self::ROOT_NAME);
            $root = $treeBuilder->getRootNode();
        }

        $root->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('identifier')
                    ->children()
                        ->scalarNode('name')->defaultValue('id')->end()
                        ->booleanNode('persistent')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('source')
                    ->children()
                        ->scalarNode('directory')->defaultValue('Fixtures/')->end()
                        ->enumNode('file_extension')->values(['csv', 'xlsx'])->defaultValue('csv')->end()
                        ->scalarNode('single_file')->defaultValue('fixtures')->end()
                        ->enumNode('names_type')->values(['column', 'field'])->defaultValue('column')->end()
                    ->end()
                ->end()
                ->scalarNode('entity_page_size')->defaultValue(1000)->end()
                ->enumNode('strategy')->values(['insert', 'upsert'])->defaultValue('insert')->end()
            ->end();

        return $treeBuilder;
    }
}
