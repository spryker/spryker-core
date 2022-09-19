<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;

interface UtilNumberServiceInterface
{
    /**
     * Specification:
     * - Requires `NumberFormatIntRequest.number` transfer property to be set.
     * - Requires `NumberFormatIntRequest.numberFormatFilter` transfer property to be set.
     * - If `NumberFormatIntRequest.numberFormatFilter.numberFormatStyle` is set, uses provided style.
     * - Otherwise, uses style provided by module config.
     * - If `NumberFormatIntRequest.numberFormatFilter.maxFractionDigits` is set, uses provided value.
     * - Otherwise, uses the value provided by module config.
     * - Formats given integer to a string according to the given locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer
     *
     * @return string
     */
    public function formatInt(NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer): string;

    /**
     * Specification:
     * - Requires `NumberFormatIntRequest.number` transfer property to be set.
     * - Requires `NumberFormatIntRequest.numberFormatFilter` transfer property to be set.
     * - If `NumberFormatIntRequest.numberFormatFilter.numberFormatStyle` is set, uses provided style.
     * - Otherwise, uses style provided by module config.
     * - If `NumberFormatIntRequest.numberFormatFilter.maxFractionDigits` is set, uses provided value.
     * - Otherwise, uses the value provided by module config.
     * - Formats given float to a string according to the given locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer
     *
     * @return string
     */
    public function formatFloat(NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer): string;

    /**
     * Specification:
     * - Returns number formatting configuration according to passed locale.
     * - If locale is not passed, returns the default number formatting configuration.
     *
     * @api
     *
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer;
}
