<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Formatter;

use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;

class DateResponseColumnValueFormatter implements DateResponseColumnValueFormatterInterface
{
    /**
     * @var \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(GuiTableToUtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function formatColumnValue($value)
    {
        return $value ? $this->utilDateTimeService->formatDateTimeToIso8601($value) : null;
    }
}
