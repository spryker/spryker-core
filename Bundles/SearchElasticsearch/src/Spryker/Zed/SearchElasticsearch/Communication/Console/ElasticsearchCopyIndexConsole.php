<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Console;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchCopyIndexConsole extends Console
{
    public const COMMAND_NAME = 'elasticsearch:index:copy';
    public const DESCRIPTION = 'This command will copy one index to another.';
    public const COMMAND_ALIAS = 'search:index:copy';

    protected const ARGUMENT_SOURCE_INDEX_NAME = 'source-index-name';
    protected const ARGUMENT_TARGET_INDEX_NAME = 'target-index-name';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->setAliases([static::COMMAND_ALIAS]);

        $this->addArgument(static::ARGUMENT_SOURCE_INDEX_NAME, InputArgument::REQUIRED, 'Name of the source Elasticsearch index to copy.');
        $this->addArgument(static::ARGUMENT_TARGET_INDEX_NAME, InputArgument::REQUIRED, 'Name of the target Elasticsearch index to copy source index to.');

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
        $sourceIndexName = $input->getArgument(static::ARGUMENT_SOURCE_INDEX_NAME);
        $targetIndexName = $input->getArgument(static::ARGUMENT_TARGET_INDEX_NAME);
        $sourceSearchContextTransfer = $this->buildSearchContextTransferFromIndexName($sourceIndexName);
        $targetSearchContextTransfer = $this->buildSearchContextTransferFromIndexName($targetIndexName);

        if ($this->getFacade()->copyIndex($sourceSearchContextTransfer, $targetSearchContextTransfer)) {
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
        return sprintf(
            'Search index "%s" is successfully copied to search index "%s".',
            $input->getArgument(static::ARGUMENT_SOURCE_INDEX_NAME),
            $input->getArgument(static::ARGUMENT_TARGET_INDEX_NAME)
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function buildErrorMessageFromInput(InputInterface $input): string
    {
        return sprintf(
            'Could not copy search index "%s" to search index "%s".',
            $input->getArgument(static::ARGUMENT_SOURCE_INDEX_NAME),
            $input->getArgument(static::ARGUMENT_TARGET_INDEX_NAME)
        );
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
