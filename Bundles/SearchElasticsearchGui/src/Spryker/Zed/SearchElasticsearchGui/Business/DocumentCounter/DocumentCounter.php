<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business\DocumentCounter;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface;

class DocumentCounter implements DocumentCounterInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
     */
    protected $searchElasticsearchFacade;

    /**
     * @param \Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface $searchElasticsearchFacade
     */
    public function __construct(SearchElasticsearchGuiToSearchElasticsearchFacadeInterface $searchElasticsearchFacade)
    {
        $this->searchElasticsearchFacade = $searchElasticsearchFacade;
    }

    /**
     * @param string $indexName
     *
     * @return int
     */
    public function getTotalCountOfDocumentsInIndex(string $indexName): int
    {
        $elasticsearchContextTransfer = (new ElasticsearchSearchContextTransfer())->setIndexName($indexName);

        return $this->searchElasticsearchFacade->getDocumentsTotalCount($elasticsearchContextTransfer);
    }
}
