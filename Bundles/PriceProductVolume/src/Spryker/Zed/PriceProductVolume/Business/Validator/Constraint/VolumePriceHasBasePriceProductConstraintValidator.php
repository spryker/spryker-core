<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VolumePriceHasBasePriceProductConstraintValidator extends ConstraintValidator
{
    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($priceProductTransfers, Constraint $constraint): void
    {
        if (!$priceProductTransfers instanceof ArrayObject) {
            throw new UnexpectedTypeException($constraint, ArrayObject::class);
        }

        if (!$constraint instanceof VolumePriceHasBasePriceProductConstraint) {
            throw new UnexpectedTypeException($constraint, VolumePriceHasBasePriceProductConstraint::class);
        }

        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
            $volumePriceProductTransfers = $constraint->getVolumePriceExtractor()
                ->extractPriceProductVolumeTransfersFromArray([$priceProductTransfer]);
            $volumePriceProductTransfers = array_values($volumePriceProductTransfers);
            $priceTypeName = $priceProductTransfer
                ->getPriceTypeOrFail()
                ->getNameOrFail();

            if (
                $moneyValueTransfer->getGrossAmount()
                || $moneyValueTransfer->getNetAmount()
                || empty($volumePriceProductTransfers)
                || $priceTypeName !== static::PRICE_TYPE_DEFAULT
            ) {
                continue;
            }

            foreach ($volumePriceProductTransfers as $volumePriceIndex => $volumePriceProductTransfer) {
                $this->context
                    ->buildViolation($constraint->getMessage())
                    ->atPath(
                        $this->createViolationPath($priceProductIndex, $volumePriceIndex)
                    )
                    ->addViolation();
            }
        }
    }

    /**
     * @param int $priceProductIndex
     * @param int $volumePriceIndex
     *
     * @return string
     */
    protected function createViolationPath(
        int $priceProductIndex,
        int $volumePriceIndex
    ): string {
        return sprintf(
            '[%d][%s][%s][%s][%d]',
            $priceProductIndex,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::PRICE_DATA,
            PriceProductVolumeConfig::VOLUME_PRICE_TYPE,
            $volumePriceIndex
        );
    }
}
