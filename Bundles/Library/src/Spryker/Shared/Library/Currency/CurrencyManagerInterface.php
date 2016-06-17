<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Shared\Library\Currency;

/**
 * This class is the central math class for currency arithmetic operations
 */
interface CurrencyManagerInterface
{

    /**
     * This method should never return a number with a thousands separator, otherwise
     * the next call to number_format will leeds to an error
     *
     * @param int $centValue
     *
     * @return float
     */
    public function convertCentToDecimal($centValue);

    /**
     * @param float $decimalValue
     *
     * @return int
     */
    public function convertDecimalToCent($decimalValue);

    /**
     * @param int|float $value
     * @param bool $includeSymbol
     *
     * @return int
     */
    public function format($value, $includeSymbol = true);

    /**
     * @param string $isoCode
     * @param int|float $value
     * @param bool $includeSymbol
     *
     * @return string
     */
    public function formatByIsoCode($isoCode, $value, $includeSymbol = true);

}
