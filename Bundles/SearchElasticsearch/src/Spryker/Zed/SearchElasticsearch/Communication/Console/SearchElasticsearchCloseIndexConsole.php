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
class SearchElasticsearchCloseIndexConsole extends AbstractSearchIndexConsole
{
    public const COMMAND_NAME = 'elasticsearch:index:close';
    public const DESCRIPTION = 'This command will close an Elasticsearch index with the specified name. If no index name is passed, all the available indices will be closed.';
    public const COMMAND_ALIAS = 'search:index:close';

    protected const ARGUMENT_INDEX_NAME = 'index-name';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->setAliases([static::COMMAND_ALIAS]);

        $this->addArgument(static::ARGUMENT_INDEX_NAME, InputArgument::OPTIONAL, 'Name of an index to be closed.');

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

        if ($this->getFacade()->closeIndex($searchContextTransfer)) {
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
            return sprintf('Search index %s is closed.', $indexName);
        }

        return 'Search indices are closed';
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
            return sprintf('Search index %s could not be closed.', $indexName);
        }

        return 'Search indices could not be closed';
    }
}
