<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Generated\Shared\Transfer\CmsSlotExternalDataTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlot\CmsSlotFactory getFactory()
 */
class CmsSlotClient extends AbstractClient implements CmsSlotClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $dataKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): CmsSlotExternalDataTransfer
    {
        return $this->getFactory()->createCmsSlotDataProvider()->getCmsSlotExternalDataByKeys($dataKeys);
    }
}
