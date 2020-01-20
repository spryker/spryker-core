<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Dependency\Facade;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;

class SearchElasticsearchGuiToSearchElasticsearchFacadeBridge implements SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface
     */
    protected $searchElasticsearchFacade;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface $searchElasticsearchFacade
     */
    public function __construct($searchElasticsearchFacade)
    {
        $this->searchElasticsearchFacade = $searchElasticsearchFacade;
    }

    /**
     * @return string[]
     */
    public function getIndexNames(): array
    {
        return $this->searchElasticsearchFacade->getIndexNames();
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return string[]
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): array
    {
        return $this->searchElasticsearchFacade->getIndexMetaData($elasticsearchSearchContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): int
    {
        return $this->searchElasticsearchFacade->getDocumentsTotalCount($elasticsearchSearchContextTransfer);
    }
}
