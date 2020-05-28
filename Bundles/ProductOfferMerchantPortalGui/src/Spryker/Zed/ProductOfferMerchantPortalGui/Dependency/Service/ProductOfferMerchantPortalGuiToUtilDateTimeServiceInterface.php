<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service;

interface ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
{
    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime): string;
}
