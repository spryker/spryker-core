<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsStorage\CmsStorageFactory getFactory()
 */
class CmsStorageClient extends AbstractClient implements CmsStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function mapCmsPageStorageData(array $data)
    {
        return $this->getFactory()
            ->createCmsPageStorageMapper()
            ->mapCmsPageStorageData($data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CmsPageStorageTransfer>
     */
    public function getCmsPageStorageByUuids(array $cmsPageUuids, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createCmsPageStorageReader()
            ->getCmsPagesByUuids($cmsPageUuids, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CmsPageStorageTransfer>
     */
    public function getCmsPageStorageByIds(array $cmsPageIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createCmsPageStorageReader()
            ->getCmsPagesByIds($cmsPageIds, $localeName, $storeName);
    }
}
