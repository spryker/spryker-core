<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Business\Validator\Constraint;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueVolumePriceConstraintValidator extends ConstraintValidator
{
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
        if (!$constraint instanceof UniqueVolumePriceConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueVolumePriceConstraint::class);
        }

        $existingKeys = [];
        foreach ($priceProductTransfers as $priceProductIndex => $priceProductTransfer) {
            if (!$priceProductTransfer instanceof PriceProductTransfer) {
                throw new UnexpectedTypeException($priceProductTransfer, PriceProductTransfer::class);
            }

            $volumePriceProductTransfers = $constraint
                ->getVolumePriceExtractor()
                ->extractPriceProductVolumeTransfersFromArray([$priceProductTransfer]);

            foreach ($volumePriceProductTransfers as $volumePriceIndex => $volumePriceProductTransfer) {
                $moneyValueTransfer = $volumePriceProductTransfer->getMoneyValueOrFail();
                if (!$moneyValueTransfer->getFkCurrency() || !$moneyValueTransfer->getFkStore()) {
                    continue;
                }

                $key = $this->createUniqueKey($moneyValueTransfer, $volumePriceProductTransfer);

                if (in_array($key, $existingKeys, true)) {
                    $this->context->buildViolation($constraint->getMessage())
                        ->atPath($this->createValidationPath($priceProductIndex, $volumePriceIndex))
                        ->addViolation();
                }

                $existingKeys[] = $key;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     *
     * @return string
     */
    protected function createUniqueKey(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductTransfer $volumePriceProductTransfer
    ): string {
        return sprintf(
            '%s-%s-%s-%d',
            $moneyValueTransfer->getFkCurrencyOrFail(),
            $moneyValueTransfer->getFkStoreOrFail(),
            $volumePriceProductTransfer->getPriceTypeOrFail()->getIdPriceTypeOrFail(),
            $volumePriceProductTransfer->getVolumeQuantityOrFail()
        );
    }

    /**
     * @param int $priceProductIndex
     * @param int $volumePriceIndex
     *
     * @return string
     */
    protected function createValidationPath(
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
