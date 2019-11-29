<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Console;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchCloseIndexConsole extends AbstractIndexNameAwareSearchIndexConsole
{
    public const COMMAND_NAME = 'elasticsearch:index:close';
    public const DESCRIPTION = 'This command will close an Elasticsearch index with the specified name. If no index name is passed, all available indexes for the current store will be closed.';
    public const COMMAND_ALIAS = 'search:index:close';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->setAliases([static::COMMAND_ALIAS]);

        $this->addArgument(static::ARGUMENT_INDEX_NAME, InputArgument::OPTIONAL, 'Name of an index to be closed.');

        parent::configure();
    }

    /**
     * @param string $indexName
     *
     * @return int
     */
    protected function executeForSingleIndex(string $indexName): int
    {
        $searchContextTransfer = $this->buildSearchContextTransferFromIndexName($indexName);

        if ($this->getFacade()->closeIndex($searchContextTransfer)) {
            $this->info(sprintf('Search index "%s" is closed.', $indexName));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Search index "%s" could not be closed.', $indexName));

        return static::CODE_ERROR;
    }

    /**
     * @return int
     */
    protected function executeForAllIndexes(): int
    {
        if ($this->getFacade()->closeIndexes()) {
            $this->info('Search indexes are closed');

            return static::CODE_SUCCESS;
        }

        $this->error('Search indexes could not be closed');

        return static::CODE_ERROR;
    }

    /**
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function buildSearchContextTransferFromIndexName(string $indexName): SearchContextTransfer
    {
        $elasticsearchSearchContext = new ElasticsearchSearchContextTransfer();
        $elasticsearchSearchContext->setIndexName($indexName);

        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContext);

        return $searchContextTransfer;
    }
}
