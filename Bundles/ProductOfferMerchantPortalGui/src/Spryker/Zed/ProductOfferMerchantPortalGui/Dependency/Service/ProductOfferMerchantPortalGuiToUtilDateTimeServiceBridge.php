<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service;

class ProductOfferMerchantPortalGuiToUtilDateTimeServiceBridge implements ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
{
    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct($utilDateTimeService)
    {
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime): string
    {
        return $this->utilDateTimeService->formatDateTimeToIso8601($dateTime);
    }

    /**
     * @param \DateTime|string $dateTime
     *
     * @return string
     */
    public function formatToDbDateTime($dateTime): string
    {
        return $this->utilDateTimeService->formatToDbDateTime($dateTime);
    }
}
