<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\EventReader;

interface MerchantOmsEventReaderInterface
{
    /**
     * @param int $idMerchantOrder
     *
     * @return string[]
     */
    public function getManualEventsByIdMerchantOrder(int $idMerchantOrder): array;
}
