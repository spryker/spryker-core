<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\ConstraintProvider;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductOffer\Business\Constraint\GreaterThanOrEqualOrEmptyConstraint;
use Spryker\Zed\PriceProductOffer\Business\Constraint\TransferConstraint;
use Spryker\Zed\PriceProductOffer\Business\Constraint\ValidUniqueStoreCurrencyCollectionConstraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints\All as AllConstraint;

class PriceProductOfferConstraintProvider implements PriceProductOfferConstraintProviderInterface
{
    protected const VALUE_IS_INVALID = 'This value is not valid.';

    /**
     * @var \Symfony\Component\Validator\Constraint[]
     */
    protected $priceProductTransferConstraintsExtension;

    /**
     * @param \Symfony\Component\Validator\Constraint[] $priceProductTransferConstraintsExtension
     */
    public function __construct(array $priceProductTransferConstraintsExtension)
    {
        $this->priceProductTransferConstraintsExtension = $priceProductTransferConstraintsExtension;
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
                    $this->priceProductTransferConstraintsExtension
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
}
