<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Formatter;

use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;

interface NumberFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer
     *
     * @return string
     */
    public function formatInt(NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer
     *
     * @return string
     */
    public function formatFloat(NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer): string;
}
