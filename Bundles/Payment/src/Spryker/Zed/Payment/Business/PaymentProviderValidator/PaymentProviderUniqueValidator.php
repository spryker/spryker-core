<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\PaymentProviderValidator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface;

class PaymentProviderUniqueValidator implements PaymentProviderValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_KEY_IS_USED = 'Payment provider key "%paymentProviderKey%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_KEY = '%paymentProviderKey%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_IS_USED = 'Payment provider name "%paymentProviderName%" used more then once among requested entities.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NAME = '%paymentProviderName%';

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface
     */
    protected $paymentProviderEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder
     */
    public function __construct(
        PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder
    ) {
        $this->paymentProviderEntityIdentifierBuilder = $paymentProviderEntityIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function validate(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        $paymentProviderKeys = [];
        $paymentProviderNames = [];

        foreach ($paymentProviderCollectionResponseTransfer->getPaymentProviders() as $paymentProviderTransfer) {
            $paymentProviderCollectionResponseTransfer = $this->validateThatPaymentProviderKeyIsUnique(
                $paymentProviderCollectionResponseTransfer,
                $paymentProviderTransfer,
                $paymentProviderKeys,
            );
            $paymentProviderCollectionResponseTransfer = $this->validateThatPaymentProviderNameIsUnique(
                $paymentProviderCollectionResponseTransfer,
                $paymentProviderTransfer,
                $paymentProviderNames,
            );
            $paymentProviderKey = $paymentProviderTransfer->getPaymentProviderKeyOrFail();
            $paymentProviderName = $paymentProviderTransfer->getNameOrFail();
            $paymentProviderKeys[$paymentProviderKey] = $paymentProviderKey;
            $paymentProviderNames[$paymentProviderName] = $paymentProviderName;
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param array<string, string> $paymentProviderKeys
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validateThatPaymentProviderKeyIsUnique(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        PaymentProviderTransfer $paymentProviderTransfer,
        array $paymentProviderKeys
    ): PaymentProviderCollectionResponseTransfer {
        if (isset($paymentProviderKeys[$paymentProviderTransfer->getPaymentProviderKeyOrFail()])) {
            $paymentProviderCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentProviderEntityIdentifierBuilder->buildEntityIdentifier($paymentProviderTransfer))
                    ->setMessage(static::ERROR_MESSAGE_KEY_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_KEY => $paymentProviderTransfer->getPaymentProviderKeyOrFail()]),
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param array<string, string> $paymentProviderNames
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validateThatPaymentProviderNameIsUnique(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        PaymentProviderTransfer $paymentProviderTransfer,
        array $paymentProviderNames
    ): PaymentProviderCollectionResponseTransfer {
        if (isset($paymentProviderNames[$paymentProviderTransfer->getNameOrFail()])) {
            $paymentProviderCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setEntityIdentifier($this->paymentProviderEntityIdentifierBuilder->buildEntityIdentifier($paymentProviderTransfer))
                    ->setMessage(static::ERROR_MESSAGE_NAME_IS_USED)
                    ->setParameters([static::ERROR_MESSAGE_PARAMETER_NAME => $paymentProviderTransfer->getNameOrFail()]),
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }
}
