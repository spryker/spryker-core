<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStorage;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;

interface CmsSlotStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves CmsSlotStorageTransfer by the given key.
     * - Returns null if CMS slot is not present in the storage.
     *
     * @api
     *
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer|null
     */
    public function findCmsSlotByKey(string $cmsSlotKey): ?CmsSlotStorageTransfer;
}
