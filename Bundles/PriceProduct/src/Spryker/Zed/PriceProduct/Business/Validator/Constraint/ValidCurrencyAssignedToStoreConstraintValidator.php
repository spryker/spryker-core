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

class ValidCurrencyAssignedToStoreConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof PriceProductTransfer) {
            throw new UnexpectedTypeException($value, PriceProductTransfer::class);
        }

        if (!$constraint instanceof ValidCurrencyAssignedToStoreConstraint) {
            throw new UnexpectedTypeException($constraint, ValidCurrencyAssignedToStoreConstraint::class);
        }
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $value->getMoneyValueOrFail();

        if (!$moneyValueTransfer->getFkStore() || !$moneyValueTransfer->getCurrency()) {
            return;
        }

        /** @var int $idStore */
        $idStore = $moneyValueTransfer->requireFkStore()->getFkStore();
        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $constraint->getStoreFacade()->getStoreById($idStore);
        /** @var string $storeName */
        $storeName = $storeTransfer->getName();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();
        /** @var string $currencyName */
        $currencyName = $currencyTransfer->getName();

        if (!in_array($moneyValueTransfer->getCurrencyOrFail()->getCode(), $storeTransfer->getAvailableCurrencyIsoCodes(), true)) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ currency }}', $currencyName)
                ->setParameter('{{ store }}', $storeName)
                ->addViolation();
        }
    }
}
