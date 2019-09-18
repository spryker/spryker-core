<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Generated\Shared\Transfer\CmsSlotExternalDataTransfer;

interface CmsSlotClientInterface
{
    /**
     * Specification:
     * - Finds the values by the given keys.
     * - Fills the CmsSlotExternalDataTransfer::values with the keys from $dataKeys and values which are provided by Spryker\Client\CmsSlotExtension\Dependency\Plugin\ExternalDataProviderStrategyPluginInterface plugins.
     * - Returns the CmsSlotExternalDataTransfer with the obtained values.
     *
     * @api
     *
     * @param string[] $dataKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): CmsSlotExternalDataTransfer;
}
