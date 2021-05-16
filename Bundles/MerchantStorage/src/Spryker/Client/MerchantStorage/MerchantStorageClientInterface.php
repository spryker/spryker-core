<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
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
     * - Finds one merchant storage by a merchant criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantStorageTransfer;

    /**
     * Specification:
     * - Finds merchant storage data by a merchant criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function get(MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer): array;
}
