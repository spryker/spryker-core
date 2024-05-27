<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer;

interface MerchantCommissionGroupReaderInterface
{
    /**
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollectionByUuids(array $uuids): MerchantCommissionGroupCollectionTransfer;

    /**
     * @param list<string> $merchantCommissionGroupKeys
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollectionByKeys(array $merchantCommissionGroupKeys): MerchantCommissionGroupCollectionTransfer;
}
