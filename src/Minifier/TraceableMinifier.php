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

namespace Sensiolabs\MinifyBundle\Minifier;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
final class TraceableMinifier implements MinifierInterface
{
    public function __construct(
        private readonly MinifierInterface $minifier,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function minify(string $input, string $type/* , ?OptionsInterface $options = null */): string
    {
        $options = \func_num_args() > 2 ? \func_get_arg(2) : null;

        $inputSize = strlen($input);
        $this->logger->debug('Minify command input: {inputSize} kB', [
            'inputSize' => round($inputSize / 1024, 1),
            'input' => $input,
        ]);

        $timeStart = microtime(true);
        $output = null === $options
                    ? $this->minifier->minify($input, $type)
                    : $this->minifier->minify($input, $type, $options);
        $timeEnd = microtime(true);

        $outputSize = strlen($output);
        $this->logger->debug('Minify command output: {outputSize} kB', [
            'output' => $output,
            'outputSize' => round($outputSize / 1024, 1),
        ]);

        $this->logger->info('Minified asset type {type}: reduced {ratio} % in {duration} ms.', [
            'type' => $type,
            'ratio' => abs((int) ($inputSize > 0 ? ($outputSize - $inputSize) / $inputSize * 100 : 0)),
            'duration' => (int) (($timeEnd - $timeStart) * 1000),
            'sizeDiff' => round(($outputSize - $inputSize) / 1024, 1),
        ]);

        return $output;
    }
}
