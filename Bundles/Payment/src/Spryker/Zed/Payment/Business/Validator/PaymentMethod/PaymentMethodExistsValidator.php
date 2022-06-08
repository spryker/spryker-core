<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Validator\PaymentMethod;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodExistsValidator implements PaymentMethodValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_KEY_EXISTS = 'Payment method with key "%paymentMethodKey%" already exists.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAM_KEY = '%paymentMethodKey%';

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface
     */
    protected $paymentMethodEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->paymentMethodEntityIdentifierBuilder = $paymentMethodEntityIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function validate(PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer): PaymentMethodCollectionResponseTransfer
    {
        foreach ($paymentMethodCollectionResponseTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            if ($this->hasPaymentMethod($paymentMethodTransfer)) {
                $paymentMethodCollectionResponseTransfer = $this->addErrorToPaymentMethodCollectionResponseTransfer(
                    $paymentMethodTransfer,
                    $paymentMethodCollectionResponseTransfer,
                );
            }
        }

        return $paymentMethodCollectionResponseTransfer;
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
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    protected function addErrorToPaymentMethodCollectionResponseTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
    ): PaymentMethodCollectionResponseTransfer {
        $errorTransfer = (new ErrorTransfer())
            ->setEntityIdentifier($this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer))
            ->setMessage(static::ERROR_MESSAGE_KEY_EXISTS)
            ->setParameters([static::ERROR_MESSAGE_PARAM_KEY => $paymentMethodTransfer->getPaymentMethodKeyOrFail()]);

        return $paymentMethodCollectionResponseTransfer->addError($errorTransfer);
    }
}
