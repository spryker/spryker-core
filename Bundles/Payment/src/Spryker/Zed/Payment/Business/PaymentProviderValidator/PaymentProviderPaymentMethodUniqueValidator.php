<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\PaymentProviderValidator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;

class PaymentProviderPaymentMethodUniqueValidator implements PaymentProviderValidatorInterface
{
 /**
  * @var string
  */
    protected const ERROR_MESSAGE_METHOD_KEY_IS_USED = 'Payment method key "%paymentMethodKey%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_METHOD_KEY = '%paymentMethodKey%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_METHOD_NAME_IS_USED = 'Payment method name "%paymentMethodName%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_METHOD_NAME = '%paymentMethodName%';

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
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function validate(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        $paymentMethodTransfers = $this->extractPaymentMethodsFromPaymentProviders($paymentProviderCollectionResponseTransfer);

        return $this->validatePaymentMethodsUniqueness(
            $paymentProviderCollectionResponseTransfer,
            $paymentMethodTransfers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return array<\Generated\Shared\Transfer\PaymentMethodTransfer>
     */
    protected function extractPaymentMethodsFromPaymentProviders(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): array {
        $paymentMethodTransfers = [];

        foreach ($paymentProviderCollectionResponseTransfer->getPaymentProviders() as $paymentProviderTransfer) {
            $paymentMethodTransfers = array_merge($paymentMethodTransfers, $this->extractPaymentMethodsFromPaymentProvider($paymentProviderTransfer));
        }

        return $paymentMethodTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return array<\Generated\Shared\Transfer\PaymentMethodTransfer>
     */
    protected function extractPaymentMethodsFromPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): array
    {
        $paymentMethodTransfers = [];

        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $paymentMethodTransfers[] = (clone $paymentMethodTransfer)->setPaymentProvider($paymentProviderTransfer);
        }

        return $paymentMethodTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param array<\Generated\Shared\Transfer\PaymentMethodTransfer> $paymentMethodTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validatePaymentMethodsUniqueness(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        array $paymentMethodTransfers
    ): PaymentProviderCollectionResponseTransfer {
        $paymentMethodKeys = [];
        $paymentMethodNames = [];

        foreach ($paymentMethodTransfers as $paymentMethodTransfer) {
            $paymentMethodKey = $paymentMethodTransfer->getPaymentMethodKeyOrFail();
            $paymentMethodName = $paymentMethodTransfer->getNameOrFail();
            $paymentProviderCollectionResponseTransfer = $this->validateThatPaymentMethodKeyIsUnique(
                $paymentProviderCollectionResponseTransfer,
                $paymentMethodTransfer,
                $paymentMethodKeys,
            );
            $paymentProviderCollectionResponseTransfer = $this->validateThatPaymentMethodNameIsUnique(
                $paymentProviderCollectionResponseTransfer,
                $paymentMethodTransfer,
                $paymentMethodNames,
            );
            $paymentMethodKeys[$paymentMethodKey] = $paymentMethodKey;
            $paymentMethodNames[$paymentMethodName] = $paymentMethodName;
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param array<string, string> $paymentMethodKeys
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validateThatPaymentMethodKeyIsUnique(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        PaymentMethodTransfer $paymentMethodTransfer,
        array $paymentMethodKeys
    ): PaymentProviderCollectionResponseTransfer {
        if (isset($paymentMethodKeys[$paymentMethodTransfer->getPaymentMethodKeyOrFail()])) {
            $paymentProviderCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer))
                    ->setMessage(static::ERROR_MESSAGE_METHOD_KEY_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_METHOD_KEY => $paymentMethodTransfer->getPaymentMethodKeyOrFail()]),
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param array $paymentMethodNames
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validateThatPaymentMethodNameIsUnique(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        PaymentMethodTransfer $paymentMethodTransfer,
        array $paymentMethodNames
    ): PaymentProviderCollectionResponseTransfer {
        if (isset($paymentMethodNames[$paymentMethodTransfer->getNameOrFail()])) {
            $paymentProviderCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer))
                    ->setMessage(static::ERROR_MESSAGE_METHOD_NAME_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_METHOD_NAME => $paymentMethodTransfer->getNameOrFail()]),
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }
}
