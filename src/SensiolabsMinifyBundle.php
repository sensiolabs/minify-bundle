<?php

declare(strict_types=1);

/*
 * This file is part of the SensioLabs MinifyBundle package.
 *
 * (c) Simon André - Sensiolabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensiolabs\MinifyBundle;

use Sensiolabs\MinifyBundle\Minifier\TraceableMinifier;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * @author Simon André <smn.andre@gmail.com>
 *
 * @phpstan-ignore-file
 */
final class SensiolabsMinifyBundle extends AbstractBundle
{
    /**
     * @param array<string, array<string, mixed>> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        $container->services()
            ->get('.sensiolabs_minify.minifier.minify_installer')
                ->arg(0, $config['minify']['download_directory'])

            ->get('.sensiolabs_minify.minifier.minify_factory')
                ->arg(0, $config['minify']['local_binary'])

            ->get('sensiolabs_minify.asset_mapper.compiler')
                ->arg(1, array_keys(array_filter($config['asset_mapper']['types']), boolval(...)))
                ->arg(2, $config['asset_mapper']['ignore_paths'])
                ->arg(3, $config['asset_mapper']['ignore_vendor'])
        ;

        if (!$config['asset_mapper']['enabled']) {
            $container->services()
                ->remove('sensiolabs_minify.asset_mapper.compiler')
            ;
        }

        if (!$config['minify']['download_binary']) {
            $container->services()
                ->remove('.sensiolabs_minify.minifier.minify_installer')
                ->remove('.sensiolabs_minify.command.minify_install')
            ;
        }

        if ($builder->getParameter('kernel.debug')) {
            $container->services()
                ->set('sensiolabs_minify.traceable_minifier', TraceableMinifier::class)
                ->decorate('sensiolabs_minify.minifier')
                ->args([
                    service('sensiolabs_minify.traceable_minifier.inner'),
                    service('logger')->nullOnInvalid(),
                ])
                ->tag('monolog.logger', ['channel' => 'assets'])
            ;
        }
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition
            ->rootNode()
            ->children()
                ->arrayNode('asset_mapper')
                    ->info('AssetMapper compiler settings')
                    ->addDefaultsIfNotSet()
                    ->canBeDisabled()
                    ->children()
                        ->arrayNode('types')
                            ->info('Asset types to minify')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('css')->defaultTrue()->end()
                                ->booleanNode('js')->defaultTrue()->end()
                            ->end()
                        ->end()
                        ->arrayNode('ignore_paths')
                            ->info('Paths to exclude from minification')
                            ->example(['admin/*', '*.min.*'])
                            ->beforeNormalization()->castToArray()->end()
                            ->scalarPrototype()->end()
                        ->end()
                        ->booleanNode('ignore_vendor')
                            ->info('Exclude vendor assets from minification')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('minify')
                    ->info('Minify settings')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('local_binary')
                            ->info('Path to the local binary (use "auto" for automatic detection)')
                            ->defaultValue(false)
                        ->end()
                        ->booleanNode('download_binary')
                            ->info('Download the binary from GitHub (defaults to "true" in debug mode)')
                            ->defaultValue('%kernel.debug%')
                        ->end()
                        ->scalarNode('download_directory')
                            ->info('Directory to store the downloaded binary')
                            ->defaultValue('%kernel.project_dir%/var/minify')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
