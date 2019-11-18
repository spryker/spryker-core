<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlotBlockStorage\CmsSlotBlockStorageFactory getFactory()
 */
class CmsSlotBlockStorageClient extends AbstractClient implements CmsSlotBlockStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer|null
     */
    public function findCmsSlotBlockStorageData(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): ?CmsSlotBlockStorageDataTransfer {
        return $this->getFactory()
            ->createCmsSlotBlockStorageReader()
            ->findCmsSlotBlockStorageData($cmsSlotTemplatePath, $cmsSlotKey);
    }
}
