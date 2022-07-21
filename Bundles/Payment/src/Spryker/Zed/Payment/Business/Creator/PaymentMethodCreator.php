<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;

class PaymentMethodCreator implements PaymentMethodCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $paymentEntityManager;

    /**
     * @var \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface
     */
    protected $paymentMethodValidator;

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface
     */
    protected $paymentMethodEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Business\Validator\PaymentMethod\PaymentMethodValidatorInterface $paymentMethodValidator
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentMethodValidatorInterface $paymentMethodValidator,
        PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->paymentMethodValidator = $paymentMethodValidator;
        $this->paymentMethodEntityIdentifierBuilder = $paymentMethodEntityIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    public function createPaymentMethodCollection(
        PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
    ): PaymentMethodCollectionResponseTransfer {
        $this->assertRequiredFields($paymentMethodCollectionRequestTransfer);

        $paymentMethodTransfers = $paymentMethodCollectionRequestTransfer->getPaymentMethods();
        $paymentMethodCollectionResponseTransfer = (new PaymentMethodCollectionResponseTransfer())
            ->setPaymentMethods(new ArrayObject($paymentMethodTransfers->getArrayCopy()));

        $paymentMethodCollectionResponseTransfer = $this->paymentMethodValidator->validate($paymentMethodCollectionResponseTransfer);

        if ($paymentMethodCollectionRequestTransfer->getIsTransactional() && $paymentMethodCollectionResponseTransfer->getErrors()->count()) {
            return $paymentMethodCollectionResponseTransfer;
        }

        $entityIdentifiers = $this->extractEntityIdentifiersFromErrorTransfers($paymentMethodCollectionResponseTransfer->getErrors());
        $paymentMethodCollectionResponseTransfer = $this->filterOutInvalidPaymentMethods(
            $paymentMethodCollectionResponseTransfer,
            $entityIdentifiers,
        );

        return $this->getTransactionHandler()->handleTransaction(function () use ($paymentMethodCollectionResponseTransfer) {
            return $this->executeCreatePaymentMethodCollectionTransaction($paymentMethodCollectionResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(PaymentMethodCollectionRequestTransfer $paymentMethodCollectionRequestTransfer): void
    {
        $paymentMethodCollectionRequestTransfer
            ->requirePaymentMethods()
            ->requireIsTransactional();

        foreach ($paymentMethodCollectionRequestTransfer->getPaymentMethods() as $paymentMethodTransfer) {
            $paymentMethodTransfer
                ->requireName()
                ->requirePaymentMethodKey()
                ->requireIdPaymentProvider();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    protected function executeCreatePaymentMethodCollectionTransaction(
        PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
    ): PaymentMethodCollectionResponseTransfer {
        foreach ($paymentMethodCollectionResponseTransfer->getPaymentMethods() as $index => $paymentMethodTransfer) {
            $paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetSet(
                $index,
                $this->paymentEntityManager->createPaymentMethod($paymentMethodTransfer),
            );
        }

        return $paymentMethodCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer
     * @param array $entityIdentifiers
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionResponseTransfer
     */
    protected function filterOutInvalidPaymentMethods(
        PaymentMethodCollectionResponseTransfer $paymentMethodCollectionResponseTransfer,
        array $entityIdentifiers
    ): PaymentMethodCollectionResponseTransfer {
        if (!$entityIdentifiers) {
            return $paymentMethodCollectionResponseTransfer;
        }

        $paymentMethodTransfers = $paymentMethodCollectionResponseTransfer->getPaymentMethods();
        foreach ($paymentMethodTransfers as $key => $paymentMethodTransfer) {
            $entityIdentifier = $this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer);
            if (in_array($entityIdentifier, $entityIdentifiers, true)) {
                $paymentMethodTransfers->offsetUnset($key);
            }
        }

        return $paymentMethodCollectionResponseTransfer->setPaymentMethods($paymentMethodTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string>
     */
    protected function extractEntityIdentifiersFromErrorTransfers(ArrayObject $errorTransfers): array
    {
        $entityIdentifiers = [];
        foreach ($errorTransfers as $errorTransfer) {
            $entityIdentifiers[] = $errorTransfer->getEntityIdentifierOrFail();
        }

        return $entityIdentifiers;
    }
}
