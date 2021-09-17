<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client;

class CmsPagesContentBannersResourceRelationshipToCmsStorageClientBridge implements CmsPagesContentBannersResourceRelationshipToCmsStorageClientInterface
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
     * @param array<string> $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CmsPageStorageTransfer>
     */
    public function getCmsPageStorageByUuids(array $cmsPageUuids, string $localeName, string $storeName): array
    {
        return $this->cmsStorageClient->getCmsPageStorageByUuids($cmsPageUuids, $localeName, $storeName);
    }
}
