<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Service;

use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

class ProductLabelToUtilDateTimeBridge implements ProductLabelToUtilDateTimeInterface
{

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $dateTimeService;

    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $dateTimeService
     */
    public function __construct(UtilDateTimeServiceInterface $dateTimeService)
    {
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param string $dateTime
     *
     * @return \DateTime
     */
    public function fromString($dateTime)
    {
        return $this->dateTimeService->fromString($dateTime);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    public function formatDateTime($date)
    {
        return $this->dateTimeService->formatDateTime($date);
    }

}
