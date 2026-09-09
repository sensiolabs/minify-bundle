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

use Sensiolabs\MinifyBundle\Exception\RuntimeException;
use Sensiolabs\MinifyBundle\Minifier\MinifierInterface;
use Sensiolabs\MinifyBundle\Minifier\Options\OptionsInterface;
use Symfony\Component\Process\Process;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
final class Minify implements MinifierInterface
{
    public function __construct(
        private readonly string $binaryPath,
    ) {
    }

    /**
     * @param OptionsInterface|null $options
     */
    public function minify(string $input, string $type/* , ?OptionsInterface $options = null */): string
    {
        $options = \func_num_args() > 2 ? \func_get_arg(2) : null;
        if (null !== $options && !$options instanceof OptionsInterface) {
            throw new RuntimeException(sprintf('Expected $options to be an instance of "%s", got "%s".', OptionsInterface::class, get_debug_type($options)));
        }
        if (null !== $options && $options->getType() !== $type) {
            throw new RuntimeException(sprintf('Options type "%s" does not match minify type "%s".', $options->getType(), $type));
        }

        $args = [$this->binaryPath, '--type', $type];
        if (null !== $options) {
            $args = [...$args, ...$options->toCliArgs()];
        }

        $process = new Process($args);
        $process->setInput($input);

        try {
            $process->run();
        } catch (\Throwable $e) {
            throw new RuntimeException('Error during minify command: '.$e->getMessage(), 0, $e);
        }

        if (!$process->isSuccessful()) {
            throw new RuntimeException(sprintf('Minify error %s: "%s".', $process->getExitCode(), $process->getExitCodeText()));
        }

        return $process->getOutput();
    }
}
