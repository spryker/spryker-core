<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class NameLengthMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const NAME_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const NAME_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH = 'merchant_commission.validation.merchant_commission_name_invalid_length';

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
            if ($this->isNameLengthValid($merchantCommissionTransfer->getNameOrFail())) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_NAME_INVALID_LENGTH,
                [
                    static::GLOSSARY_KEY_PARAMETER_MIN => static::NAME_MIN_LENGTH,
                    static::GLOSSARY_KEY_PARAMETER_MAX => static::NAME_MAX_LENGTH,
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isNameLengthValid(string $name): bool
    {
        return mb_strlen($name) >= static::NAME_MIN_LENGTH
            && mb_strlen($name) <= static::NAME_MAX_LENGTH;
    }
}
