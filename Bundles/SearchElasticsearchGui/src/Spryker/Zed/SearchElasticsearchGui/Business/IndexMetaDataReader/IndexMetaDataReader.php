<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business\IndexMetaDataReader;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Spryker\Zed\SearchElasticsearchGui\Dependency\Facade\SearchElasticsearchGuiToSearchElasticsearchFacadeInterface;

class IndexMetaDataReader implements IndexMetaDataReaderInterface
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
     * @return array
     */
    public function getIndexMetaData(string $indexName): array
    {
        $elasticsearchContextTransfer = (new ElasticsearchSearchContextTransfer())->setIndexName($indexName);

        return $this->searchElasticsearchFacade->getIndexMetaData($elasticsearchContextTransfer);
    }
}
