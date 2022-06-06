<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\PaymentProviderValidator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderConditionsTransfer;
use Generated\Shared\Transfer\PaymentProviderCriteriaTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentProviderExistsValidator implements PaymentProviderValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_KEY_EXISTS = 'Payment provider with key "%paymentProviderKey%" already exists.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAM_KEY = '%paymentProviderKey%';

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
            if ($this->hasPaymentProvider($paymentProviderTransfer)) {
                $paymentProviderCollectionResponseTransfer = $this->addErrorToPaymentProviderCollectionResponseTransfer(
                    $paymentProviderTransfer,
                    $paymentProviderCollectionResponseTransfer,
                );
            }
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return bool
     */
    protected function hasPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): bool
    {
        $paymentMethodConditionsTransfer = (new PaymentProviderConditionsTransfer())->addPaymentProviderKey($paymentProviderTransfer->getPaymentProviderKeyOrFail());
        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions($paymentMethodConditionsTransfer);

        return $this->paymentRepository->hasPaymentProvider($paymentProviderCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function addErrorToPaymentProviderCollectionResponseTransfer(
        PaymentProviderTransfer $paymentProviderTransfer,
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        $errorTransfer = (new ErrorTransfer())
            ->setEntityIdentifier($this->paymentProviderEntityIdentifierBuilder->buildEntityIdentifier($paymentProviderTransfer))
            ->setMessage(static::ERROR_MESSAGE_KEY_EXISTS)
            ->setParameters([static::ERROR_MESSAGE_PARAM_KEY => $paymentProviderTransfer->getPaymentProviderKeyOrFail()]);

        return $paymentProviderCollectionResponseTransfer->addError($errorTransfer);
    }
}
