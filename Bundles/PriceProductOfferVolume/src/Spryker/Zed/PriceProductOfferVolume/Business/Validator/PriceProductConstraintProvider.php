<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductOfferVolume\Business\Validator\Constraint\TransferConstraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class PriceProductConstraintProvider implements PriceProductConstraintProviderInterface
{
    /**
     * @var string
     */
    protected const VALUE_IS_INVALID = 'This value is not valid.';

    /**
     * @var string
     */
    protected const VOLUME_QUANTITY_IS_INVALID = 'Invalid volume quantity.';

    /**
     * @var array<\Symfony\Component\Validator\Constraint>
     */
    protected $priceProductTransferConstraints;

    /**
     * @param array<\Symfony\Component\Validator\Constraint> $priceProductTransferConstraints
     */
    public function __construct(array $priceProductTransferConstraints)
    {
        $this->priceProductTransferConstraints = $priceProductTransferConstraints;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    public function getConstraints(): array
    {
        return array_merge(
            $this->priceProductTransferConstraints,
            [
                new TransferConstraint([
                    PriceProductTransfer::MONEY_VALUE => $this->getMoneyValueTransferConstraint(),
                    PriceProductTransfer::VOLUME_QUANTITY => $this->getVolumeQuantityConstraint(),
                ]),
            ],
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getMoneyValueTransferConstraint(): SymfonyConstraint
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
        return new GreaterThanOrEqual([
            'value' => 0,
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getGrossAmountConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqual([
            'value' => 0,
            'message' => static::VALUE_IS_INVALID,
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function getVolumeQuantityConstraint(): SymfonyConstraint
    {
        return new GreaterThanOrEqual([
            'value' => 1,
            'message' => static::VOLUME_QUANTITY_IS_INVALID,
        ]);
    }
}
