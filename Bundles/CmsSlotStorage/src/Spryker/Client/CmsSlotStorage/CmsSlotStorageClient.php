<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStorage;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlotStorage\CmsSlotStorageFactory getFactory()
 */
class CmsSlotStorageClient extends AbstractClient implements CmsSlotStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer|null
     */
    public function findSlotByKey(string $cmsSlotKey): ?CmsSlotStorageTransfer
    {
        return $this->getFactory()
            ->createCmsSlotStorageStorageReader()
            ->findSlotByKey($cmsSlotKey);
    }
}
