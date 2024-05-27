<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

interface MerchantCommissionGroupDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer> $merchantCommissionGroupTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupUuids(ArrayObject $merchantCommissionGroupTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer> $merchantCommissionGroupTransfers
     *
     * @return list<string>
     */
    public function extractUniqueMerchantCommissionGroupKeys(ArrayObject $merchantCommissionGroupTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupUuidsFromMerchantCommissionTransfers(ArrayObject $merchantCommissionTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupKeysFromMerchantCommissionTransfers(ArrayObject $merchantCommissionTransfers): array;
}
