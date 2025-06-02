<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Error\Error;

/**
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class TwigTemplateWarmerConsole extends Console
{
    /**
     * @var string
     */
    public const NAME = 'twig:template:warmer';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Warm up Twig templates by loading them.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setDescription(static::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $templateMap = $this->getFactory()
            ->createFilesystemCacheLoader()
            ->load();

        if (!$templateMap) {
            $output->writeln(sprintf('<info>Path cache is empty, nothing to warm up with `%s`.</info>', static::NAME));

            return static::CODE_SUCCESS;
        }

        $twig = $this->getFactory()->getTwigEnvironment();

        $compiled = 0;
        $errors = 0;

        foreach ($templateMap as $tplName => $tplPath) {
            if ($tplPath === false) {
                continue;
            }

            try {
                if ($output->isVerbose()) {
                    $output->writeln(sprintf('<info>Compiling template: %s</info>', $tplName));
                }

                $twig->load($tplName);
                $compiled++;
            } catch (Error $error) {
                $output->writeln(sprintf(
                    '<fg=red>failed:</fg=red> %s -> %s',
                    $tplName,
                    $error->getMessage(),
                ));
                $errors++;
            }
        }

        $output->writeln(sprintf('compiled: %d, failed: %d', $compiled, $errors));

        return static::CODE_SUCCESS;
    }
}
