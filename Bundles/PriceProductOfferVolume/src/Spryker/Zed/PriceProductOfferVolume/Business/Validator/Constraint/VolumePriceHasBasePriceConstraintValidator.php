<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferVolume\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VolumePriceHasBasePriceConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $priceProductOfferTransfer = $value;
        if (!$priceProductOfferTransfer instanceof PriceProductOfferTransfer) {
            throw new UnexpectedTypeException($priceProductOfferTransfer, PriceProductOfferTransfer::class);
        }

        if (!$constraint instanceof VolumePriceHasBasePriceConstraint) {
            throw new UnexpectedTypeException($constraint, VolumePriceHasBasePriceConstraint::class);
        }

        $priceProductTransfers = $priceProductOfferTransfer->getProductOfferOrFail()->getPrices();

        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
            if (
                $moneyValueTransfer->getGrossAmount()
                || $moneyValueTransfer->getNetAmount()
                || !$constraint->getPriceProductVolumeService()->hasVolumePrices($priceProductTransfer)
            ) {
                continue;
            }

            $this->context
                ->buildViolation($constraint->getMessage())
                ->atPath(
                    $this->createViolationPath($priceProductIndex),
                )
                ->addViolation();
        }
    }

    /**
     * @param int $priceProductIndex
     *
     * @return string
     */
    protected function createViolationPath(int $priceProductIndex): string
    {
        return sprintf(
            '[%s][%s][%d]',
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex,
        );
    }
}
