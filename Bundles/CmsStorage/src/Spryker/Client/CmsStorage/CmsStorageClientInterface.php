<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage;

interface CmsStorageClientInterface
{
    /**
     * Specification:
     * - Maps raw CMS page storage data to transfer object.
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function mapCmsPageStorageData(array $data);

    /**
     * Specification:
     * - Finds CMS page storage records by UUIDs, locale and store.
     *
     * @api
     *
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPageStorageByUuids(array $cmsPageUuids, string $localeName, string $storeName): array;

    /**
     * Specification:
     * - Finds CMS page storage records by Ids, locale and store.
     *
     * @api
     *
     * @param int[] $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPageStorageByIds(array $cmsPageIds, string $localeName, string $storeName): array;
}
