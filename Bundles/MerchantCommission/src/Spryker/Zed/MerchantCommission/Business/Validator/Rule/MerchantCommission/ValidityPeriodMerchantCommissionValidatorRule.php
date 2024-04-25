<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class ValidityPeriodMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID = 'merchant_commission.validation.merchant_commission_validity_period_invalid';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            if ($merchantCommissionTransfer->getValidFrom() === null || $merchantCommissionTransfer->getValidTo() === null) {
                continue;
            }

            if ($this->isValidPeriod($merchantCommissionTransfer->getValidFromOrFail(), $merchantCommissionTransfer->getValidToOrFail())) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_VALIDITY_PERIOD_INVALID,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $validFrom
     * @param string $validTo
     *
     * @return bool
     */
    protected function isValidPeriod(string $validFrom, string $validTo): bool
    {
        $validFromDateTime = new DateTime($validFrom);
        $validToDateTime = new DateTime($validTo);

        return $validTo > $validFrom;
    }
}
