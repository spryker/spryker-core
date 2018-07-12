<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Dependency\Service;

class OfferGuiToUtilDateTimeServiceBridge implements OfferGuiToUtilDateTimeServiceInterface
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
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date): string
    {
        return $this->utilDateTimeService->formatDateTime($date);
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date): string
    {
        return $this->utilDateTimeService->formatDate($date);
    }
}
