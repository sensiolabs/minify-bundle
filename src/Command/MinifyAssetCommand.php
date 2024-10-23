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

namespace Sensiolabs\MinifyBundle\Command;

use Sensiolabs\MinifyBundle\Minifier\MinifierInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
#[AsCommand('minify:asset')]
final class MinifyAssetCommand extends Command
{
    public function __construct(
        private MinifierInterface $minifier,
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'Asset filename, relative to the project directory (e.g. <comment>assets/css/styles.css)</comment>')
            ->addArgument('output', InputArgument::OPTIONAL, 'Output filename, relative to the project directory (e.g. <comment>public/css/styles.min.css</comment>)')
            ->addOption('type', 't', InputOption::VALUE_REQUIRED, 'Asset type: <comment>css</comment> or <comment>js</comment>. If not provided, the file extension will be used.')
            ->setHelp(
                <<<'EOF'
The <info>%command.name%</info> command minifies an asset file.

    <info>php %command.full_name% assets/css/asset.css</info>

The minified asset will be output to the console.
To write the minified result into a file, pass the <comment>output</comment> 
filename as the second argument:

    <info>php %command.full_name% assets/css/asset.css public/css/asset.min.css</info>   

You can also specify the type of the asset with the <comment>--type</comment> option:

    <info>php %command.full_name% assets/js/asset.jsm --type=js public/js/asset.js</info>

<fg=bright-blue>INFORMATION</>
If you're using AssetMapper, the assets will be minified automatically
during the "<comment>asset-map:compile</comment>" command.
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();

        /** @var string $inputArg */
        $inputArg = $input->getArgument('input');
        if (Path::isRelative($inputArg)) {
            $inputArg = Path::join($this->projectDir, $inputArg);
        }
        if (!$fs->exists($inputArg)) {
            $io->error(sprintf('Cannot read file "%s".', $inputArg));

            return Command::FAILURE;
        }
        /** @var string $inputArg */
        $inputArg = file_get_contents($inputArg);

        /** @var string|null $outputArg */
        $outputArg = $input->getArgument('output');
        if ($outputArg && Path::isRelative($outputArg)) {
            $outputArg = Path::join($this->projectDir, $outputArg);
        }

        /** @var 'css'|'js' $typeArg */
        $typeArg = $input->getOption('type') ?? pathinfo($inputArg, PATHINFO_EXTENSION);

        $output = $this->minifier->minify($inputArg, $typeArg);

        if (null === $outputArg) {
            $io->text($output);

            return Command::SUCCESS;
        }

        $fs = new Filesystem();
        try {
            $fs->dumpFile($outputArg, $output);
        } catch (IOException) {
            $io->error(sprintf('Cannot write to file "%s".', $outputArg));

            return Command::FAILURE;
        }

        $io->success(sprintf('Asset minified into "%s".', $outputArg));

        return Command::SUCCESS;
    }
}
