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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sensiolabs\MinifyBundle\AssetMapper\MinifierCompiler;
use Sensiolabs\MinifyBundle\Command\MinifyAssetCommand;
use Sensiolabs\MinifyBundle\Command\MinifyInstallCommand;
use Sensiolabs\MinifyBundle\Minifier\MinifierInterface;
use Sensiolabs\MinifyBundle\Minify;
use Sensiolabs\MinifyBundle\MinifyFactory;
use Sensiolabs\MinifyBundle\MinifyInstaller;

return static function (ContainerConfigurator $container) {
    $container->services()

        ->set('.sensiolabs_minify.minifier.minify_installer', MinifyInstaller::class)
            ->args([
                abstract_arg('download_path'),
                abstract_arg('download_version'),
                service('http_client')->nullOnInvalid(),
            ])

        ->set('.sensiolabs_minify.minifier.minify_factory', MinifyFactory::class)
            ->args([
                abstract_arg('local_binary'),
                service('.sensiolabs_minify.minifier.minify_installer')->nullOnInvalid(),
            ])

         ->set('sensiolabs_minify.minifier.minify', Minify::class)
            ->factory([service('.sensiolabs_minify.minifier.minify_factory'), 'create'])

        ->alias('sensiolabs_minify.minifier', 'sensiolabs_minify.minifier.minify')

        ->alias(MinifierInterface::class, 'sensiolabs_minify.minifier')

        ->set('sensiolabs_minify.asset_mapper.compiler', MinifierCompiler::class)
            ->args([service('sensiolabs_minify.minifier')])
            ->tag('asset_mapper.compiler', ['priority' => -1024])

        ->set('.sensiolabs_minify.command.minify_install', MinifyInstallCommand::class)
            ->args([service('.sensiolabs_minify.minifier.minify_installer')])
            ->tag('console.command')

        ->set('.sensiolabs_minify.command.minify_asset', MinifyAssetCommand::class)
            ->args([
                service('sensiolabs_minify.minifier'),
                param('kernel.project_dir'),
            ])
            ->tag('console.command')
    ;
};
