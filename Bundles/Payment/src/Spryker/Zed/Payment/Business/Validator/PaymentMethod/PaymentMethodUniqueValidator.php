<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Validator\PaymentMethod;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;

class PaymentMethodUniqueValidator implements PaymentMethodValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_KEY_IS_USED = 'Payment method key "%paymentMethodKey%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_IS_USED = 'Payment method name "%paymentMethodName%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_KEY = '%paymentMethodKey%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NAME = '%paymentMethodName%';

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface
     */
    protected $paymentMethodEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
     */
    public function __construct(
        PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
    ) {
        $this->paymentMethodEntityIdentifierBuilder = $paymentMethodEntityIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function validate(PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer): PaymentMethodCollectionResponseTransfer
    {
        $paymentMethodKeys = [];
        $paymentMethodNames = [];

        foreach ($paymentMethodCollectionResponseTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $paymentMethodKey = $paymentMethodTransfer->getPaymentMethodKeyOrFail();
            $paymentMethodName = $paymentMethodTransfer->getNameOrFail();
            $paymentMethodCollectionResponseTransfer = $this->validateThatPaymentMethodKeyIsUnique(
                $paymentMethodCollectionResponseTransfer,
                $paymentMethodTransfer,
                $paymentMethodKeys,
            );
            $paymentMethodCollectionResponseTransfer = $this->validateThatPaymentMethodNameIsUnique(
                $paymentMethodCollectionResponseTransfer,
                $paymentMethodTransfer,
                $paymentMethodNames,
            );
            $paymentMethodKeys[$paymentMethodKey] = $paymentMethodKey;
            $paymentMethodNames[$paymentMethodName] = $paymentMethodName;
        }

        return $paymentMethodCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param array $paymentMethodKeys
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    protected function validateThatPaymentMethodKeyIsUnique(
        PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer,
        PaymentMethodTransfer $paymentMethodTransfer,
        array $paymentMethodKeys
    ): PaymentMethodCollectionResponseTransfer {
        if (isset($paymentMethodKeys[$paymentMethodTransfer->getPaymentMethodKeyOrFail()])) {
            $paymentMethodCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer))
                    ->setMessage(static::ERROR_MESSAGE_KEY_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_KEY => $paymentMethodTransfer->getPaymentMethodKeyOrFail()]),
            );
        }

        return $paymentMethodCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param array<string, string> $paymentMethodNames
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    protected function validateThatPaymentMethodNameIsUnique(
        PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer,
        PaymentMethodTransfer $paymentMethodTransfer,
        array $paymentMethodNames
    ): PaymentMethodCollectionResponseTransfer {
        if (isset($paymentMethodNames[$paymentMethodTransfer->getNameOrFail()])) {
            $paymentMethodCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer))
                    ->setMessage(static::ERROR_MESSAGE_NAME_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_NAME => $paymentMethodTransfer->getNameOrFail()]),
            );
        }

        return $paymentMethodCollectionResponseTransfer;
    }
}
