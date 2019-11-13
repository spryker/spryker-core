<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStorage\Reader;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;

interface CmsSlotStorageReaderInterface
{
    /**
     * @param string $cmsSlotKey
     *
     * @throws \Spryker\Client\CmsSlotStorage\Exception\CmsSlotNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    public function getCmsSlotByKey(string $cmsSlotKey): CmsSlotStorageTransfer;
}
