<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class KeyLengthMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const KEY_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const KEY_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_INVALID_LENGTH = 'merchant_commission.validation.merchant_commission_key_invalid_length';

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
            if ($this->isKeyLengthValid($merchantCommissionTransfer->getKeyOrFail())) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_INVALID_LENGTH,
                [
                    static::GLOSSARY_KEY_PARAMETER_MIN => static::KEY_MIN_LENGTH,
                    static::GLOSSARY_KEY_PARAMETER_MAX => static::KEY_MAX_LENGTH,
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isKeyLengthValid(string $key): bool
    {
        return mb_strlen($key) >= static::KEY_MIN_LENGTH
            && mb_strlen($key) <= static::KEY_MAX_LENGTH;
    }
}
