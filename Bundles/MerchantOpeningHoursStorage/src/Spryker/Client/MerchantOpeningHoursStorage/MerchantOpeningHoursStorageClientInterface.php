<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

interface MerchantOpeningHoursStorageClientInterface
{
    /**
     * Specification:
     * - Finds merchant opening hours within Storage with a given merchant ID.
     * - Returns null if merchant opening hours was not found.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer|null
     */
    public function findMerchantOpeningHoursByIdMerchant(int $idMerchant): ?MerchantOpeningHoursStorageTransfer;
}
