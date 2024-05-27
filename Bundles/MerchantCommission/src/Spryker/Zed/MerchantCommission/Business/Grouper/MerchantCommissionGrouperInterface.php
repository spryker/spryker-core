<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;

interface MerchantCommissionGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    public function groupMerchantCommissionsByValidity(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    public function groupMerchantCommissionsByPersistenceExistence(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $baseMerchantCommissionTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $additionalMerchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function mergeMerchantCommissionTransfers(
        ArrayObject $baseMerchantCommissionTransfers,
        ArrayObject $additionalMerchantCommissionTransfers
    ): ArrayObject;
}
