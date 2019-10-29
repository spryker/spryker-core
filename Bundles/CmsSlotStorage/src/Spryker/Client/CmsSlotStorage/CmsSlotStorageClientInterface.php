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
     * - Throws an exception if CMS slot is not present in the storage.
     *
     * @api
     *
     * @param string $cmsSlotKey
     *
     * @throws \Spryker\Client\CmsSlotStorage\Exception\CmsSlotNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    public function getCmsSlotByKey(string $cmsSlotKey): CmsSlotStorageTransfer;
}
