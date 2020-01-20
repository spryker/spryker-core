<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business;

interface MerchantOpeningHoursStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes merchant opening hours changes to storage.
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function publish(array $merchantIds): void;
}
