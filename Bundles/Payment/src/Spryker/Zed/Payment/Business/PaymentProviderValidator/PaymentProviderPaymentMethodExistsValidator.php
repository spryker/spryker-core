<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\PaymentProviderValidator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentProviderPaymentMethodExistsValidator implements PaymentProviderValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_METHOD_KEY_EXISTS = 'Payment method with key "%paymentMethodKey%" already exists.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_METHOD_KEY = '%paymentMethodKey%';

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface
     */
    protected $paymentProviderEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder
    ) {
        $this->paymentRepository = $paymentRepository;
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
        foreach ($paymentProviderCollectionResponseTransfer->getPaymentProviders() as $paymentProviderTransfer) {
            $paymentProviderCollectionResponseTransfer = $this->validatePaymentMethods(
                $paymentProviderTransfer,
                $paymentProviderCollectionResponseTransfer,
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function validatePaymentMethods(
        PaymentProviderTransfer $paymentProviderTransfer,
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        foreach ($paymentProviderTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            if ($this->hasPaymentMethod($paymentMethodTransfer)) {
                $paymentProviderCollectionResponseTransfer = $this->addErrorToPaymentProviderCollectionResponseTransfer(
                    $paymentProviderTransfer,
                    $paymentMethodTransfer,
                    $paymentProviderCollectionResponseTransfer,
                );
            }
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function hasPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        $paymentMethodConditionsTransfer = (new PaymentMethodConditionsTransfer())->addPaymentMethodKey($paymentMethodTransfer->getPaymentMethodKeyOrFail());
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        return $this->paymentRepository->hasPaymentMethod($paymentMethodCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function addErrorToPaymentProviderCollectionResponseTransfer(
        PaymentProviderTransfer $paymentProviderTransfer,
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        $errorTransfer = (new ErrorTransfer())
            ->setEntityIdentifier($this->paymentProviderEntityIdentifierBuilder->buildEntityIdentifier($paymentProviderTransfer))
            ->setMessage(static::ERROR_MESSAGE_METHOD_KEY_EXISTS)
            ->setParameters([static::ERROR_MESSAGE_PARAMETER_METHOD_KEY => $paymentMethodTransfer->getPaymentMethodKeyOrFail()]);

        return $paymentProviderCollectionResponseTransfer->addError($errorTransfer);
    }
}
