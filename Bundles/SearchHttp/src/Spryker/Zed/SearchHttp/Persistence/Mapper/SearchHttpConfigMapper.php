<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence\Mapper;

use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;
use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig;

class SearchHttpConfigMapper implements SearchHttpConfigMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer
     * @param \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig $searchHttpConfigEntity
     *
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig
     */
    public function mapSearchHttpConfigTransferCollectionToSearchHttpConfigEntity(
        SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer,
        SpySearchHttpConfig $searchHttpConfigEntity
    ): SpySearchHttpConfig {
        return $searchHttpConfigEntity->setData($searchHttpConfigCollectionTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig $searchHttpConfigEntity
     * @param \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    public function mapSearchHttpConfigEntityToSearchHttpConfigCollection(
        SpySearchHttpConfig $searchHttpConfigEntity,
        SearchHttpConfigCollectionTransfer $searchHttpConfigCollectionTransfer
    ): SearchHttpConfigCollectionTransfer {
        return $searchHttpConfigCollectionTransfer->fromArray($searchHttpConfigEntity->getData());
    }
}
