<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Traversable;

class ValidUniqueStoreCurrencyCollectionConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param \Traversable<int, \Generated\Shared\Transfer\PriceProductTransfer> $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Traversable) {
            throw new UnexpectedTypeException($value, Traversable::class);
        }

        if (!$constraint instanceof ValidUniqueStoreCurrencyCollectionConstraint) {
            throw new UnexpectedTypeException($constraint, ValidUniqueStoreCurrencyCollectionConstraint::class);
        }

        $existingKeys = [];

        foreach ($value as $position => $priceProductTransfer) {
            if (!$priceProductTransfer instanceof PriceProductTransfer) {
                throw new UnexpectedTypeException($priceProductTransfer, PriceProductTransfer::class);
            }

            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if (!$moneyValueTransfer->getFkCurrency() || !$moneyValueTransfer->getFkStore()) {
                continue;
            }

            $key = $this->createPriceProductGroupKey($constraint, $priceProductTransfer);

            if (in_array($key, $existingKeys, true)) {
                $this->context->buildViolation($constraint->getMessage())
                    ->atPath("[$position]")
                    ->addViolation();
            }

            $existingKeys[] = $key;
        }
    }

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Validator\Constraint\ValidUniqueStoreCurrencyCollectionConstraint $constraint
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function createPriceProductGroupKey(Constraint $constraint, PriceProductTransfer $priceProductTransfer): string
    {
        $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimensionOrFail();

        $clonePriceProductDimensionTransfer = clone $priceProductDimensionTransfer;

        $clonedPriceProductTransfer = (clone $priceProductTransfer)
            ->setPriceTypeName($priceProductTransfer->getPriceTypeOrFail()->getName())
            ->setPriceDimension($clonePriceProductDimensionTransfer->setName(null));

        return $constraint->getPriceProductService()->buildPriceProductGroupKey($clonedPriceProductTransfer);
    }
}
