<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Service;

interface MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface
{
    /**
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToUtcIso8601($dateTime, ?string $timezone = null): string;
}
