<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Reader;

interface CmsPageStorageReaderInterface
{
    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\CmsPageStorageTransfer>
     *
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPagesByUuids(array $cmsPageUuids, string $localeName, string $storeName): array;

    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\CmsPageStorageTransfer>
     *
     * @param int[] $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPagesByIds(array $cmsPageIds, string $localeName, string $storeName): array;
}
