<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Builder;

use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Converter\DecimalToCentConverter;
use Spryker\Shared\Money\Converter\DecimalToCentConverterInterface;
use Spryker\Shared\Money\DataMapper\MoneyToTransferConverterInterface;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;

class MoneyBuilder implements MoneyBuilderInterface
{

    /**
     * @var \Spryker\Shared\Money\DataMapper\MoneyToTransferConverterInterface
     */
    protected $dataMapper;

    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToCentConverterInterface
     */
    protected $decimalToCentConverter;

    /**
     * @var string
     */
    protected $defaultCurrency;

    /**
     * @param \Spryker\Shared\Money\DataMapper\MoneyToTransferConverterInterface $moneyToTransferConverter
     * @param \Spryker\Shared\Money\Converter\DecimalToCentConverterInterface $decimalToCentConverter
     * @param string $defaultCurrency
     */
    public function __construct(
        MoneyToTransferConverterInterface $moneyToTransferConverter,
        DecimalToCentConverterInterface $decimalToCentConverter,
        $defaultCurrency
    ) {

        $this->dataMapper = $moneyToTransferConverter;
        $this->decimalToCentConverter = $decimalToCentConverter;
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @param int $amount
     * @param null|string $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $currency = null)
    {
        if (!is_int($amount)) {
            throw new InvalidAmountArgumentException(sprintf(
                'Current amount was expected to be int. Current type is "%s"',
                gettype($amount)
            ));
        }

        return $this->getMoney($amount, $currency);
    }

    /**
     * @param float $amount
     * @param null|string $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $currency = null)
    {
        if (!is_float($amount)) {
            throw new InvalidAmountArgumentException(sprintf(
                'Current amount was expected to be float. Current type is "%s"',
                gettype($amount)
            ));
        }

        return $this->getMoney($this->decimalToCentConverter->convert($amount), $currency);
    }

    /**
     * @param string $amount
     * @param null|string $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $currency = null)
    {
        if (!is_string($amount)) {
            throw new InvalidAmountArgumentException(sprintf(
                'Current amount was expected to be string. Current type is "%s"',
                gettype($amount)
            ));
        }
        if (strstr($amount, ',') || strstr($amount, '.')) {
            throw new InvalidAmountArgumentException('Amount as string should not contain decimals or a thousand separator!');
        }

        return $this->getMoney($amount, $currency);
    }

    /**
     * @param int|string $amount
     * @param string null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoney($amount, $currency = null)
    {
        $currency = $this->getCurrency($currency);
        $money = $this->createMoney($amount, $currency);
        $moneyTransfer = $this->convertToTransfer($money);

        return $moneyTransfer;
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
        return $this->dataMapper->convert($money);
    }

}
