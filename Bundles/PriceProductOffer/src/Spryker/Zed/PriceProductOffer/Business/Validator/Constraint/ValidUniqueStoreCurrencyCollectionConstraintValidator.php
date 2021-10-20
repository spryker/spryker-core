<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Traversable;

class ValidUniqueStoreCurrencyCollectionConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @phpstan-param \Traversable<int, \Generated\Shared\Transfer\PriceProductOfferTransfer> $priceProductOfferTransfers
     *
     * @param \Traversable $priceProductOfferTransfers
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($priceProductOfferTransfers, Constraint $constraint): void
    {
        if (!$priceProductOfferTransfers instanceof Traversable) {
            throw new UnexpectedTypeException($priceProductOfferTransfers, Traversable::class);
        }

        if (!$constraint instanceof ValidUniqueStoreCurrencyCollectionConstraint) {
            throw new UnexpectedTypeException($constraint, ValidUniqueStoreCurrencyCollectionConstraint::class);
        }

        foreach ($priceProductOfferTransfers as $priceProductOfferIndex => $priceProductOfferTransfer) {
            if (!$priceProductOfferTransfer instanceof PriceProductOfferTransfer) {
                throw new UnexpectedTypeException($priceProductOfferTransfer, PriceProductOfferTransfer::class);
            }

            $existingKeys = [];
            $priceProductTransfers = $priceProductOfferTransfer->getProductOfferOrFail()->getPrices();

            foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
                $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();
                if (!$moneyValueTransfer->getFkCurrency() || !$moneyValueTransfer->getFkStore()) {
                    continue;
                }

                $key = $this->createUniqueKey($moneyValueTransfer, $priceProductTransfer);
                if (in_array($key, $existingKeys, true)) {
                    $this->context
                        ->buildViolation($constraint->getMessage())
                        ->atPath(
                            $this->createViolationPath($priceProductOfferIndex, $priceProductIndex),
                        )
                        ->addViolation();
                }

                $existingKeys[] = $key;
            }
        }
    }

    /**
     * @param int $priceProductOfferIndex
     * @param int $priceProductIndex
     *
     * @return string
     */
    protected function createViolationPath(
        int $priceProductOfferIndex,
        int $priceProductIndex
    ): string {
        return sprintf(
            '[%d][%s][%s][%d]',
            $priceProductOfferIndex,
            PriceProductOfferTransfer::PRODUCT_OFFER,
            ProductOfferTransfer::PRICES,
            $priceProductIndex,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function createUniqueKey(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductTransfer $priceProductTransfer
    ): string {
        return sprintf(
            '%s-%s-%s',
            $moneyValueTransfer->getFkCurrencyOrFail(),
            $moneyValueTransfer->getFkStoreOrFail(),
            $priceProductTransfer->getPriceTypeOrFail()->getIdPriceTypeOrFail(),
        );
    }
}
