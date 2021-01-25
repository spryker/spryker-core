<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Search\Communication\Console\SearchSetupSourcesConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchSetupIndexesConsole extends Console
{
    public const COMMAND_NAME = 'search:setup:indexes';
    public const DESCRIPTION = 'This command will create the search indexes.';
    public const COMMAND_NAME_SETUP_SEARCH_ALIAS = 'setup:search:indexes';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->setAliases([static::COMMAND_NAME_SETUP_SEARCH_ALIAS]);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->installIndexes($this->getMessenger());

        return static::CODE_SUCCESS;
    }
}
