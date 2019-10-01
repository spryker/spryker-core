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
     * - Retrieves cms slot storage transfers according to given key.
     *
     * @api
     *
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer|null
     */
    public function findSlotByKey(string $cmsSlotKey): ?CmsSlotStorageTransfer;
}
