<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class PriorityRangeMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const PRIORITY_RANGE_MIN = 1;

    /**
     * @var int
     */
    protected const PRIORITY_RANGE_MAX = 9999;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE = 'merchant_commission.validation.merchant_commission_priority_not_in_range';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MIN = '%min%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MAX = '%max%';

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
            if (
                !$merchantCommissionTransfer->getPriority()
                || $this->isPriorityInRange($merchantCommissionTransfer->getPriorityOrFail())
            ) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_PRIORITY_NOT_IN_RANGE,
                [
                    static::GLOSSARY_KEY_PARAMETER_MIN => static::PRIORITY_RANGE_MIN,
                    static::GLOSSARY_KEY_PARAMETER_MAX => static::PRIORITY_RANGE_MAX,
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param int $priority
     *
     * @return bool
     */
    protected function isPriorityInRange(int $priority): bool
    {
        return $priority >= static::PRIORITY_RANGE_MIN
            && $priority <= static::PRIORITY_RANGE_MAX;
    }
}
