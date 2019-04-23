<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class CleanTranslationCacheConsole extends Console
{
    public const COMMAND_NAME = 'translator:clean-cache';
    public const DESCRIPTION = 'Clean translation cache for Zed';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->cleanTranslationCache();
    }
}
