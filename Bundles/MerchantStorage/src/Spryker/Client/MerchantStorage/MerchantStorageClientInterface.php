<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage;

use Generated\Shared\Transfer\MerchantStorageTransfer;

interface MerchantStorageClientInterface
{
    /**
     * Specification:
     * - Maps raw merchant storage data to transfer object.
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function mapMerchantStorageData(array $data): MerchantStorageTransfer;

    /**
     * Specification:
     * - Finds merchant storage data by idMerchant.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(int $idMerchant): ?MerchantStorageTransfer;

    /**
     * Specification:
     * - Finds merchant storage data by merchantIds.
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function find(array $merchantIds): array;
}
