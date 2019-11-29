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
class ElasticsearchDeleteIndexConsole extends AbstractIndexNameAwareSearchIndexConsole
{
    public const COMMAND_NAME = 'elasticsearch:index:delete';
    public const DESCRIPTION = 'This command will delete Elasticsearch index by its name. If no index name is specified, all available indexes for the current store will be deleted.';
    public const COMMAND_ALIAS = 'search:index:delete';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->addArgument(static::ARGUMENT_INDEX_NAME, InputArgument::OPTIONAL, 'Name of an index to be deleted.');
        $this->setAliases([static::COMMAND_ALIAS]);

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

        if ($this->getFacade()->deleteIndex($searchContextTransfer)) {
            $this->info(sprintf('Search index "%s" successfully deleted.', $indexName));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Search index "%s" could not be deleted.', $indexName));

        return static::CODE_ERROR;
    }

    /**
     * @return int
     */
    protected function executeForAllIndexes(): int
    {
        if ($this->getFacade()->deleteIndexes()) {
            $this->info('Search indexes are successfully deleted');

            return static::CODE_SUCCESS;
        }

        $this->error('Search indexes could not be deleted');

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
