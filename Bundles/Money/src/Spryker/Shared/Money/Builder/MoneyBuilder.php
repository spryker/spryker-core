<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Builder;

use Money\Currency;
use Money\Money;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use Spryker\Shared\Money\Exception\InvalidAmountArgumentException;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface;

class MoneyBuilder implements MoneyBuilderInterface
{
    /**
     * @var \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface
     */
    protected $dataMapper;

    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    protected $decimalToIntegerConverter;

    /**
     * @var string
     */
    protected $defaultIsoCode;

    /**
     * @param \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface $moneyToTransferConverter
     * @param \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface $decimalToIntegerConverter
     * @param string $defaultIsoCode
     */
    public function __construct(
        MoneyToTransferMapperInterface $moneyToTransferConverter,
        DecimalToIntegerConverterInterface $decimalToIntegerConverter,
        $defaultIsoCode
    ) {
        $this->dataMapper = $moneyToTransferConverter;
        $this->decimalToIntegerConverter = $decimalToIntegerConverter;
        $this->defaultIsoCode = $defaultIsoCode;
    }

    /**
     * @param int $amount
     * @param string|null $isoCode
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidAmountArgumentException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $isoCode = null)
    {
        if (!is_int($amount)) {
            throw new InvalidAmountArgumentException(sprintf(
                'Current amount was expected to be int. Current type is "%s"',
                gettype($amount)
            ));
        }

        return $this->getMoney($amount, $isoCode);
    }

    /**
     * @param float $amount
     * @param string|null $isoCode
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidAmountArgumentException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $isoCode = null)
    {
        if (!is_float($amount)) {
            throw new InvalidAmountArgumentException(sprintf(
                'Current amount was expected to be float. Current type is "%s"',
                gettype($amount)
            ));
        }

        return $this->getMoney($this->decimalToIntegerConverter->convert($amount), $isoCode);
    }

    /**
     * @param string $amount
     * @param string|null $isoCode
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidAmountArgumentException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $isoCode = null)
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

        return $this->getMoney($amount, $isoCode);
    }

    /**
     * @param int|string $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoney($amount, $isoCode = null)
    {
        $isoCode = $this->getCurrency($isoCode);
        $money = $this->createMoney($amount, $isoCode);
        $moneyTransfer = $this->convertToTransfer($money);

        return $moneyTransfer;
    }

    /**
     * @param string|null $isoCode
     *
     * @return \Money\Currency
     */
    protected function getCurrency($isoCode = null)
    {
        if (!$isoCode) {
            $isoCode = $this->defaultIsoCode;
        }

        return new Currency($isoCode);
    }

    /**
     * @param int $amount
     * @param \Money\Currency $isoCode
     *
     * @return \Money\Money
     */
    protected function createMoney($amount, Currency $isoCode)
    {
        return new Money($amount, $isoCode);
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
