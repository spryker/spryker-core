<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class SearchElasticsearchOpenIndexConsole extends AbstractSearchIndexConsole
{
    protected const COMMAND_NAME = 'elasticsearch:index:open';
    protected const DESCRIPTION = 'This command will open an index.';

    protected const ARGUMENT_INDEX_NAME = 'idnex-name';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->addArgument(static::ARGUMENT_INDEX_NAME, InputArgument::OPTIONAL, 'Name of an index to be opened.');

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
        $indexName = $input->getArgument(static::ARGUMENT_INDEX_NAME);
        $searchContextTransfer = $this->buildSearchContextTransferFromIndexName($indexName);

        if ($this->getFacade()->openIndex($searchContextTransfer)) {
            $this->info($this->buildInfoMessageFromInput($input));

            return static::CODE_SUCCESS;
        }

        $this->error($this->buildErrorMessageFromInput($input));

        return static::CODE_ERROR;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function buildInfoMessageFromInput(InputInterface $input): string
    {
        $indexName = $input->getArgument(static::ARGUMENT_INDEX_NAME);

        if ($indexName) {
            return sprintf('Search index %s successfully opened.', $indexName);
        }

        return 'Search indices are successfully opened';
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function buildErrorMessageFromInput(InputInterface $input): string
    {
        $indexName = $input->getArgument(static::ARGUMENT_INDEX_NAME);

        if ($indexName) {
            return sprintf('Search index %s could not be opened.', $indexName);
        }

        return 'Search indices could not be opened';
    }
}
