<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Validator\PaymentMethod;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderConditionsTransfer;
use Generated\Shared\Transfer\PaymentProviderCriteriaTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodProviderExistsValidator implements PaymentMethodValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PROVIDER_ID_NOT_EXISTS = 'Payment provider with id "%paymentProviderId%" is unknown.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_PROVIDER_ID = '%paymentProviderId%';

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
            if (!$this->hasPaymentProvider($paymentMethodTransfer)) {
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
    protected function hasPaymentProvider(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        $paymentProviderConditionsTransfer = (new PaymentProviderConditionsTransfer())->addIdPaymentProvider($paymentMethodTransfer->getIdPaymentProviderOrFail());
        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions($paymentProviderConditionsTransfer);

        return $this->paymentRepository->hasPaymentProvider($paymentProviderCriteriaTransfer);
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
            ->setMessage(static::ERROR_MESSAGE_PROVIDER_ID_NOT_EXISTS)
            ->setParameters([static::ERROR_MESSAGE_PARAMETER_PROVIDER_ID => $paymentMethodTransfer->getIdPaymentProviderOrFail()]);

        return $paymentMethodCollectionResponseTransfer->addError($errorTransfer);
    }
}
