<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Dependency\Facade;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;

interface SearchElasticsearchGuiToSearchElasticsearchFacadeInterface
{
    /**
     * @return string[]
     */
    public function getIndexNames(): array;

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return string[]
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): int;
}
