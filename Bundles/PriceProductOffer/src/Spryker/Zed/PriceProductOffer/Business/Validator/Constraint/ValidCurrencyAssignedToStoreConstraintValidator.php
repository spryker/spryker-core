<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Validator\Constraint;

use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCurrencyAssignedToStoreConstraintValidator extends AbstractConstraintValidator
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
        if (!$value instanceof PriceProductOfferTransfer) {
            throw new UnexpectedTypeException($value, PriceProductOfferTransfer::class);
        }

        if (!$constraint instanceof ValidCurrencyAssignedToStoreConstraint) {
            throw new UnexpectedTypeException($constraint, ValidCurrencyAssignedToStoreConstraint::class);
        }

        $priceProductTransfers = $value->getProductOfferOrFail()->getPrices();

        foreach ($priceProductTransfers as $priceProductTransfer) {
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if (!$moneyValueTransfer->getFkStore() || !$moneyValueTransfer->getCurrency()) {
                return;
            }

            /** @var int $idStore */
            $idStore = $moneyValueTransfer->getFkStore();
            $storeTransfer = $constraint->getStoreFacade()->getStoreById($idStore);
            /** @var string $storeName */
            $storeName = $storeTransfer->getName();
            /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
            $currencyTransfer = $moneyValueTransfer->getCurrency();
            /** @var string $currencyName */
            $currencyName = $currencyTransfer->getName();

            if (!in_array($currencyTransfer->getCode(), $storeTransfer->getAvailableCurrencyIsoCodes(), true)) {
                $this->context->buildViolation($constraint->getMessage())
                    ->setParameter('{{ currency }}', $currencyName)
                    ->setParameter('{{ store }}', $storeName)
                    ->addViolation();
            }
        }
    }
}
