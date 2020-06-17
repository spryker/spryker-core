<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client;

class ContentProductAbstractListsRestApiToCmsStorageClientBridge implements ContentProductAbstractListsRestApiToCmsStorageClientInterface
{
    /**
     * @var \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @param \Spryker\Client\CmsStorage\CmsStorageClientInterface $cmsStorageClient
     */
    public function __construct($cmsStorageClient)
    {
        $this->cmsStorageClient = $cmsStorageClient;
    }

    /**
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPageStorageByUuids(array $cmsPageUuids, string $localeName, string $storeName): array
    {
        return $this->cmsStorageClient->getCmsPageStorageByUuids($cmsPageUuids, $localeName, $storeName);
    }
}
