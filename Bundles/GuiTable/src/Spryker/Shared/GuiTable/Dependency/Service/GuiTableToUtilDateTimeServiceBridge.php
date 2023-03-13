<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Dependency\Service;

class GuiTableToUtilDateTimeServiceBridge implements GuiTableToUtilDateTimeServiceInterface
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
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime, ?string $timezone = null): string
    {
        return $this->utilDateTimeService->formatDateTimeToIso8601($dateTime, $timezone);
    }
}
