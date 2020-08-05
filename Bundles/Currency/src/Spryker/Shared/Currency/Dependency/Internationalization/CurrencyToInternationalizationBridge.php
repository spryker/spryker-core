<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Dependency\Internationalization;

/**
 * @deprecated Use {@link \Spryker\Shared\Currency\Dependency\Internationalization\CurrenciesToInternationalizationBridge} instead.
 */
class CurrencyToInternationalizationBridge implements CurrencyToInternationalizationInterface
{
    /**
     * @var \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface
     */
    protected $currencyBundle;

    /**
     * @param \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface $currencyBundle
     */
    public function __construct($currencyBundle)
    {
        $this->currencyBundle = $currencyBundle;
    }

    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getSymbolByIsoCode($isoCode)
    {
        return $this->currencyBundle->getCurrencySymbol($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getNameByIsoCode($isoCode)
    {
        return $this->currencyBundle->getCurrencyName($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return int|null
     */
    public function getFractionDigits($isoCode)
    {
        return $this->currencyBundle->getFractionDigits($isoCode);
    }
}
