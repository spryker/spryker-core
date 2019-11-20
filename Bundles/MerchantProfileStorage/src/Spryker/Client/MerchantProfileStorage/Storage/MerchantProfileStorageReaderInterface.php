<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Storage;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;

interface MerchantProfileStorageReaderInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer|null
     */
    public function findMerchantProfileStorageData(int $idMerchant): ?MerchantProfileStorageTransfer;

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function findMerchantProfileStorageList(array $merchantIds): array;
}
