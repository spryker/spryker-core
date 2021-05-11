<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\ConstraintProvider;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\GreaterThanOrEqualOrEmptyConstraint;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\TransferConstraint;
use Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidUniqueStoreCurrencyCollectionConstraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints\All as AllConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class PriceProductConstraintProvider implements PriceProductConstraintProviderInterface
{
    protected const MESSAGE_VALUE_IS_INVALID = 'This value is not valid.';
    protected const MESSAGE_VALUE_CANNOT_BE_EMPTY = 'This field is missing.';

    /**
     * @var \Symfony\Component\Validator\Constraint[]
     */
    protected $priceProductConstraints;

    /**
     * @param \Symfony\Component\Validator\Constraint[] $priceProductConstraints
     */
    public function __construct(array $priceProductConstraints)
    {
        $this->priceProductConstraints = $priceProductConstraints;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getConstraints(): array
    {
        return [
            new ValidUniqueStoreCurrencyCollectionConstraint(),
            new AllConstraint(
                array_merge(
                    [new TransferConstraint([PriceProductTransfer::MONEY_VALUE => $this->getMoneyValueConstraint()])],
                    $this->priceProductConstraints
                )
            ),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getMoneyValueConstraint(): SymfonyConstraint
    {
        return new TransferConstraint([
            MoneyValueTransfer::NET_AMOUNT => $this->getNetAmountConstraint(),
            MoneyValueTransfer::GROSS_AMOUNT => $this->getGrossAmountConstraint(),
            MoneyValueTransfer::FK_CURRENCY => $this->getCurrencyConstraint(),
            MoneyValueTransfer::FK_STORE => $this->getStoreConstraint(),
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getNetAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqualOrEmptyConstraint([
            'value' => 0,
            'message' => static::MESSAGE_VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getGrossAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqualOrEmptyConstraint([
            'value' => 0,
            'message' => static::MESSAGE_VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getCurrencyConstraint(): SymfonyConstraint
    {
        return new NotBlank([
            'message' => static::MESSAGE_VALUE_CANNOT_BE_EMPTY,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getStoreConstraint(): SymfonyConstraint
    {
        return new NotBlank([
            'message' => static::MESSAGE_VALUE_CANNOT_BE_EMPTY,
        ]);
    }
}
