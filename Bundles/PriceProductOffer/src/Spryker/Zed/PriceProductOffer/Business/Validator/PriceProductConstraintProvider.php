<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\GreaterThanOrEqualOrEmptyConstraint;
use Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\TransferConstraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class PriceProductConstraintProvider implements PriceProductConstraintProviderInterface
{
    protected const VALUE_IS_INVALID = 'This value is not valid.';

    protected const VALUE_CANNOT_BE_EMPTY = 'This field is missing.';

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\Validator\Constraint\TransferConstraint[]
     */
    public function getConstraints(): array
    {
        return [new TransferConstraint([PriceProductTransfer::MONEY_VALUE => $this->getMoneyValueConstraint()])];
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
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getGrossAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqualOrEmptyConstraint([
            'value' => 0,
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getCurrencyConstraint(): SymfonyConstraint
    {
        return new NotBlank([
            'message' => static::VALUE_CANNOT_BE_EMPTY,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getStoreConstraint(): SymfonyConstraint
    {
        return new NotBlank([
            'message' => static::VALUE_CANNOT_BE_EMPTY,
        ]);
    }
}
