<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Dependency\Service;

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
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime): string
    {
        return $this->utilDateTimeService->formatDateTimeToIso8601($dateTime);
    }
}
