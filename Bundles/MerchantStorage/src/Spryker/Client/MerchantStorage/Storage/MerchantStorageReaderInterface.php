<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Storage;

use Generated\Shared\Transfer\MerchantStorageTransfer;

interface MerchantStorageReaderInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findMerchantStorageData(int $idMerchant): ?MerchantStorageTransfer;

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function findMerchantStorageList(array $merchantIds): array;
}
