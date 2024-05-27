<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;

interface MerchantCommissionCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function createMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function createPreValidatedMerchantCommissions(ArrayObject $merchantCommissionTransfers): ArrayObject;
}
