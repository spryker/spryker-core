<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Console;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractSearchIndexConsole extends Console
{
    /**
     * @param string|null $indexName
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer|null
     */
    protected function buildSearchContextTransferFromIndexName(?string $indexName): ?SearchContextTransfer
    {
        if (!$indexName) {
            return null;
        }

        $elasticsearchSearchContext = new ElasticsearchSearchContextTransfer();
        $elasticsearchSearchContext->setIndexName($indexName);

        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContext);

        return $searchContextTransfer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    abstract protected function buildInfoMessageFromInput(InputInterface $input): string;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    abstract protected function buildErrorMessageFromInput(InputInterface $input): string;
}
