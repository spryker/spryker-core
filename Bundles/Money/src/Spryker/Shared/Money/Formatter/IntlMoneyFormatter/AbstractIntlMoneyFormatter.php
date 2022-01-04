<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter\IntlMoneyFormatter;

use Generated\Shared\Transfer\MoneyTransfer;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter as InnerFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface;

abstract class AbstractIntlMoneyFormatter implements MoneyFormatterInterface
{
    /**
     * @var \Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface
     */
    protected $converter;

    /**
     * @var array<array<\Money\Formatter\IntlMoneyFormatter>>
     */
    protected static $formatters = [];

    /**
     * @var string|null
     */
    protected static $locale;

    /**
     * @var string
     */
    protected $currentlocale;

    /**
     * @param \Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface $transferToMoneyConverter
     * @param string|null $currentLocale
     */
    public function __construct(
        TransferToMoneyMapperInterface $transferToMoneyConverter,
        ?string $currentLocale = null
    ) {
        $this->converter = $transferToMoneyConverter;
        $this->currentlocale = $currentLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer)
    {
        $locale = $this->getLocale($moneyTransfer);
        $formatter = $this->getInnerFormatter($locale);

        $money = $this->converter->convert($moneyTransfer);
        $formatted = $formatter->format($money);

        return $formatted;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    protected function getLocale(MoneyTransfer $moneyTransfer)
    {
        if ($moneyTransfer->getLocale()) {
            return $moneyTransfer->getLocale()->getLocaleName();
        }

        return $this->getCurrentLocale();
    }

    /**
     * @return string
     */
    protected function getCurrentLocale(): string
    {
        if ($this->currentlocale !== null) {
            return $this->currentlocale;
        }

        if (static::$locale === null) {
            static::$locale = $this->getLocaleFromStore();
        }

        return static::$locale;
    }

    /**
     * @param string $localeName
     *
     * @return \Money\Formatter\IntlMoneyFormatter
     */
    protected function getInnerFormatter($localeName)
    {
        if (!isset(static::$formatters[static::class][$localeName])) {
            static::$formatters[static::class][$localeName] = new InnerFormatter(
                $this->getNumberFormatter($localeName),
                $this->getIsoCurrencies(),
            );
        }

        return static::$formatters[static::class][$localeName];
    }

    /**
     * @param string $localeName
     *
     * @return \NumberFormatter
     */
    abstract protected function getNumberFormatter($localeName);

    /**
     * @return \Money\Currencies\ISOCurrencies
     */
    protected function getIsoCurrencies()
    {
        return new ISOCurrencies();
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return string
     */
    protected function getLocaleFromStore(): string
    {
        return Store::getInstance()->getCurrentLocale();
    }
}
