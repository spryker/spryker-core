<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotExternalDataTransfer;

interface CmsSlotDataProviderInterface
{
    /**
     * @param string[] $dataKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): CmsSlotExternalDataTransfer;
}
