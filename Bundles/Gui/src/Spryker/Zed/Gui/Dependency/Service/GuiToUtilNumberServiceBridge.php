<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Dependency\Service;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;

class GuiToUtilNumberServiceBridge implements GuiToUtilNumberServiceInterface
{
    /**
     * @var \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    protected $utilNumberService;

    /**
     * @param \Spryker\Service\UtilNumber\UtilNumberServiceInterface $utilNumberService
     */
    public function __construct($utilNumberService)
    {
        $this->utilNumberService = $utilNumberService;
    }

    /**
     * @param \Generated\Shared\Transfer\NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer
     *
     * @return string
     */
    public function formatInt(NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer): string
    {
        return $this->utilNumberService->formatInt($numberFormatIntRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer
     *
     * @return string
     */
    public function formatFloat(NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer): string
    {
        return $this->utilNumberService->formatFloat($numberFormatFloatRequestTransfer);
    }

    /**
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer
    {
        return $this->utilNumberService->getNumberFormatConfig($locale);
    }
}
