<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Builder;

use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Converter\MoneyToTransferConverterInterface;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;

class MoneyBuilder implements MoneyBuilderInterface
{

    /**
     * @var \Spryker\Shared\Money\Converter\MoneyToTransferConverterInterface
     */
    protected $converter;

    /**
     * @var string
     */
    protected $defaultCurrency;

    /**
     * @param \Spryker\Shared\Money\Converter\MoneyToTransferConverterInterface $moneyToTransferConverter
     * @param string $defaultCurrency
     */
    public function __construct(MoneyToTransferConverterInterface $moneyToTransferConverter, $defaultCurrency)
    {
        $this->converter = $moneyToTransferConverter;
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @param int|float|string $amount
     * @param string null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function getMoney($amount, $currency = null)
    {
        $amount = $this->convert($amount);
        $currency = $this->getCurrency($currency);

        $money = $this->createMoney($amount, $currency);
        $moneyTransfer = $this->convertToTransfer($money);

        return $moneyTransfer;
    }

    /**
     * @param int|float|string $amount
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidAmountArgumentException
     *
     * @return int
     */
    protected function convert($amount)
    {
        if (is_int($amount)) {
            return $amount;
        }

        if (is_float($amount)) {
            return (int)$amount * 100;
        }

        if (is_string($amount) && (!strstr($amount, ',') && !strstr($amount, '.'))) {
            return $amount;
        }

        throw new InvalidAmountArgumentException($this->getExceptionMessage($amount));
    }

    /**
     * @param mixed $amount
     *
     * @return string
     */
    protected function getExceptionMessage($amount)
    {
        return sprintf(
            'Current amount can not be handled properly. Method `getMoney()` can called with integer, float or string.' . PHP_EOL,
            'String representations should not contain any decimals or thousand separator.' . PHP_EOL,
            'Current type is "%s"',
            gettype($amount)
        );
    }

    /**
     * @param string|null $currency
     *
     * @return \Money\Currency
     */
    protected function getCurrency($currency = null)
    {
        if (!$currency) {
            $currency = $this->defaultCurrency;
        }

        return new Currency($currency);
    }

    /**
     * @param int $amount
     * @param \Money\Currency $currency
     *
     * @return \Money\Money
     */
    protected function createMoney($amount, Currency $currency)
    {
        return new Money($amount, $currency);
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function convertToTransfer(Money $money)
    {
        return $this->converter->convert($money);
    }

}
