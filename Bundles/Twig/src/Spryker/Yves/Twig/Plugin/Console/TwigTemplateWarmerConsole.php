<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin\Console;

use Spryker\Yves\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Error\Error;

/**
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
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

        $compiledTemplateCount = 0;
        $failedTemplateCount = 0;
        foreach ($templateMap as $templateName => $templatePath) {
            if ($templatePath === false) {
                continue;
            }

            try {
                if ($output->isVerbose()) {
                    $output->writeln(sprintf('<info>Compiling template: %s</info>', $templateName));
                }

                $twig->load($templateName);
                $compiledTemplateCount++;
            } catch (Error $error) {
                $output->writeln(sprintf(
                    '<error>failed:</error> %s -> %s',
                    $templateName,
                    $error->getMessage(),
                ));
                $failedTemplateCount++;
            }
        }

        $output->writeln(sprintf('compiled: %d, failed: %d', $compiledTemplateCount, $failedTemplateCount));

        return static::CODE_SUCCESS;
    }
}
