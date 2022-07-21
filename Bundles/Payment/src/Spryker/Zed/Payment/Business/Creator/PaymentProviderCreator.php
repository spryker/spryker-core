<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface;
use Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;

class PaymentProviderCreator implements PaymentProviderCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $paymentEntityManager;

    /**
     * @var \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface
     */
    protected $paymentProviderValidator;

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface
     */
    protected $paymentProviderEntityIdentifierBuilder;

    /**
     * @var \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface
     */
    protected $paymentMethodEntityIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Business\PaymentProviderValidator\PaymentProviderValidatorInterface $paymentProviderValidator
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder
     * @param \Spryker\Zed\Payment\Business\EntityIdentifierBuilder\PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentProviderValidatorInterface $paymentProviderValidator,
        PaymentProviderEntityIdentifierBuilderInterface $paymentProviderEntityIdentifierBuilder,
        PaymentMethodEntityIdentifierBuilderInterface $paymentMethodEntityIdentifierBuilder
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->paymentProviderValidator = $paymentProviderValidator;
        $this->paymentProviderEntityIdentifierBuilder = $paymentProviderEntityIdentifierBuilder;
        $this->paymentMethodEntityIdentifierBuilder = $paymentMethodEntityIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    public function createPaymentProviderCollection(
        PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
    ): PaymentProviderCollectionResponseTransfer {
        $this->assertRequiredFields($paymentProviderCollectionRequestTransfer);

        $paymentProviderTransfers = $paymentProviderCollectionRequestTransfer->getPaymentProviders();
        $paymentProviderCollectionResponseTransfer = (new PaymentProviderCollectionResponseTransfer())
            ->setPaymentProviders(new ArrayObject($paymentProviderTransfers->getArrayCopy()));

        $paymentProviderCollectionResponseTransfer = $this->paymentProviderValidator
            ->validate($paymentProviderCollectionResponseTransfer);

        if ($paymentProviderCollectionRequestTransfer->getIsTransactional() && $paymentProviderCollectionResponseTransfer->getErrors()->count() > 0) {
            return $paymentProviderCollectionResponseTransfer;
        }

        $entityIdentifiers = $this->extractEntityIdentifiersFromErrorTransfers($paymentProviderCollectionResponseTransfer->getErrors());
        $paymentProviderCollectionResponseTransfer = $this->filterOutInvalidPaymentProviders(
            $paymentProviderCollectionResponseTransfer,
            $entityIdentifiers,
        );

        return $this->getTransactionHandler()->handleTransaction(function () use ($paymentProviderCollectionResponseTransfer) {
            return $this->executeCreatePaymentProviderCollectionTransaction($paymentProviderCollectionResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(PaymentProviderCollectionRequestTransfer $paymentProviderCollectionRequestTransfer): void
    {
        $paymentProviderCollectionRequestTransfer
            ->requirePaymentProviders()
            ->requireIsTransactional();

        foreach ($paymentProviderCollectionRequestTransfer->getPaymentProviders() as $paymentProviderTransfer) {
            $paymentProviderTransfer
                ->requireName()
                ->requirePaymentProviderKey();

            if ($paymentProviderTransfer->getPaymentMethods()->count()) {
                $this->assertPaymentMethodRequiredFields($paymentProviderTransfer->getPaymentMethods());
            }
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PaymentMethodTransfer> $paymentMethodTransfers
     *
     * @return void
     */
    protected function assertPaymentMethodRequiredFields(ArrayObject $paymentMethodTransfers): void
    {
        foreach ($paymentMethodTransfers as $paymentMethodTransfer) {
            $paymentMethodTransfer
                ->requireName()
                ->requirePaymentMethodKey();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function executeCreatePaymentProviderCollectionTransaction(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
    ): PaymentProviderCollectionResponseTransfer {
        foreach ($paymentProviderCollectionResponseTransfer->getPaymentProviders() as $index => $paymentProviderTransfer) {
            $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetSet(
                $index,
                $this->createPaymentProvider($paymentProviderTransfer),
            );
        }

        return $paymentProviderCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer
     * @param array<string, string> $entityIdentifiers
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionResponseTransfer
     */
    protected function filterOutInvalidPaymentProviders(
        PaymentProviderCollectionResponseTransfer $paymentProviderCollectionResponseTransfer,
        array $entityIdentifiers
    ): PaymentProviderCollectionResponseTransfer {
        if (!$entityIdentifiers) {
            return $paymentProviderCollectionResponseTransfer;
        }

        $paymentProviderTransfers = $paymentProviderCollectionResponseTransfer->getPaymentProviders();
        foreach ($paymentProviderTransfers as $key => $paymentProviderTransfer) {
            $entityIdentifier = $this->paymentProviderEntityIdentifierBuilder->buildEntityIdentifier($paymentProviderTransfer);
            if (in_array($entityIdentifier, $entityIdentifiers, true)) {
                $paymentProviderTransfers->offsetUnset($key);

                continue;
            }

            $paymentProviderTransfer = $this->filterOutInvalidPaymentProviderPaymentMethods($paymentProviderTransfer, $entityIdentifiers);
            $paymentProviderTransfers->offsetSet($key, $paymentProviderTransfer);
        }

        return $paymentProviderCollectionResponseTransfer->setPaymentProviders($paymentProviderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param array<string, string> $entityIdentifiers
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected function filterOutInvalidPaymentProviderPaymentMethods(
        PaymentProviderTransfer $paymentProviderTransfer,
        array $entityIdentifiers
    ): PaymentProviderTransfer {
        if (!$entityIdentifiers) {
            return $paymentProviderTransfer;
        }

        $paymentMethodTransfers = $paymentProviderTransfer->getPaymentMethods();
        foreach ($paymentMethodTransfers as $key => $paymentMethodTransfer) {
            $entityIdentifier = $this->paymentMethodEntityIdentifierBuilder->buildEntityIdentifier($paymentMethodTransfer);
            if (in_array($entityIdentifier, $entityIdentifiers, true)) {
                $paymentMethodTransfers->offsetUnset($key);
            }
        }

        return $paymentProviderTransfer->setPaymentMethods($paymentMethodTransfers);
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

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderTransfer
    {
        $paymentProviderTransfers = $paymentProviderTransfer->getPaymentMethods();
        $paymentProviderTransfer = $this->paymentEntityManager->createPaymentProvider($paymentProviderTransfer);

        foreach ($paymentProviderTransfers as $paymentMethodTransfer) {
            $paymentMethodTransfer->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProviderOrFail());
            $paymentMethodTransfer = $this->paymentEntityManager->createPaymentMethod($paymentMethodTransfer);
            $paymentProviderTransfer->addPaymentMethod($paymentMethodTransfer);
        }

        return $paymentProviderTransfer;
    }
}
