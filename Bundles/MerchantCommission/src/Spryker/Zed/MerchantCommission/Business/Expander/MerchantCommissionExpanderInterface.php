<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;

interface MerchantCommissionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function expandMerchantCommissionCollectionWithMerchantCommissionAmounts(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
    ): MerchantCommissionCollectionTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function expandMerchantCommissionsWithMerchantCommissionGroupsByUuids(ArrayObject $merchantCommissionTransfers): ArrayObject;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function expandMerchantCommissionsWithMerchantCommissionGroupsByKeys(ArrayObject $merchantCommissionTransfers): ArrayObject;
}
