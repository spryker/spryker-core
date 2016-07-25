<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Currency;

use Spryker\Shared\Kernel\Store;

/**
 * @deprecated Use Money Bundle instead
 *
 * This class is the central math class for currency arithmetic operations
 */
class CurrencyManager implements CurrencyManagerInterface
{

    const PRICE_PRECISION = 100;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyInterface
     */
    private static $currency;

    /**
     * @var self
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * @param \Spryker\Shared\Library\Currency\CurrencyInterface $currency
     *
     * @return void
     */
    public static function setDefaultCurrency(CurrencyInterface $currency)
    {
        self::$currency = $currency;
    }

    /**
     * @deprecated
     *
     * @param string $isoCode
     *
     * @return void
     */
    public static function setDefaultCurrencyIso($isoCode)
    {
        self::$currency = self::loadCurrencyClass($isoCode);
    }

    /**
     * @deprecated Use Store class instead
     *
     * @return \Spryker\Shared\Library\Currency\CurrencyInterface
     */
    public static function getDefaultCurrency()
    {
        if (!self::$currency) {
            self::$currency = self::loadCurrencyClass(Store::getInstance()->getCurrencyIsoCode());
        }

        return self::$currency;
    }

    /**
     * @deprecated
     *
     * @param string $currencyIsoCode
     *
     * @return \Spryker\Shared\Library\Currency\CurrencyInterface
     */
    protected static function loadCurrencyClass($currencyIsoCode)
    {
        $class = '\Spryker\\Shared\\Library\\Currency\\Config\\' . $currencyIsoCode;

        return new $class();
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * This method should never return a number with a thousend separator, otherwise
     * the next call to number_format will leeds to an error
     *
     * @param int $centValue
     *
     * @return float
     */
    public function convertCentToDecimal($centValue)
    {
        return number_format($centValue / self::PRICE_PRECISION, 2, '.', '');
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * @param float $decimalValue
     *
     * @return int
     */
    public function convertDecimalToCent($decimalValue)
    {
        return $decimalValue * self::PRICE_PRECISION;
    }

    /**
     * @deprecated
     *
     * Ceil the current value
     * Solves precision lose problems, like in:
     * -((0.1+0.7)*10), ('34.200' + 0) * 100)
     * Specify expected decimalPlacesInUse to avoid false rounding
     *
     *
     * @param int|float $value
     * @param int $decimalPlacesInUse
     *
     * @return float
     */
    public static function ceil($value, $decimalPlacesInUse = 2)
    {
        return ceil(round($value, $decimalPlacesInUse + 1));
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * @param int|float $value
     * @param bool $includeSymbol
     *
     * @return int
     */
    public function format($value, $includeSymbol = true)
    {
        return self::formatCurrency($this->getDefaultCurrency(), $value, $includeSymbol);
    }

    /**
     * @deprecated Use Money Bundle instead
     *
     * @param string $isoCode
     * @param int|float $value
     * @param bool $includeSymbol
     *
     * @return string
     */
    public function formatByIsoCode($isoCode, $value, $includeSymbol = true)
    {
        return self::formatCurrency($this->loadCurrencyClass($isoCode), $value, $includeSymbol);
    }

    /**
     * @deprecated
     *
     * @param \Spryker\Shared\Library\Currency\CurrencyInterface $currency
     * @param int|float $value
     * @param bool $includeSymbol
     *
     * @return string|null
     */
    protected function formatCurrency(CurrencyInterface $currency, $value, $includeSymbol = true)
    {
        if ($value === null) {
            return $value;
        }
        $value = $this->formatNumber($currency, $value);

        if ($includeSymbol === true) {
            $value = str_replace(
                [CurrencyConstants::PLACEHOLDER_VALUE, CurrencyConstants::PLACEHOLDER_SYMBOL],
                [$value, $currency->getSymbol()],
                $currency->getFormatPattern()
            );
        }

        return $value;
    }

    /**
     * @deprecated
     *
     * @param \Spryker\Shared\Library\Currency\CurrencyInterface $currency
     * @param int|float $value
     *
     * @return string
     */
    protected function formatNumber(CurrencyInterface $currency, $value)
    {
        return number_format($value, $currency->getDecimalDigits(), $currency->getDecimalSeparator(), $currency->getThousandsSeparator());
    }

}
