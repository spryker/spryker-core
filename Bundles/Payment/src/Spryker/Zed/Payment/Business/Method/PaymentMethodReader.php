<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodReader implements PaymentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected $paymentMethodFilterPlugins;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[] $paymentMethodFilterPlugins
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(
        array $paymentMethodFilterPlugins,
        PaymentConfig $paymentConfig,
        PaymentToStoreFacadeInterface $storeFacade,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->paymentMethodFilterPlugins = $paymentMethodFilterPlugins;
        $this->paymentConfig = $paymentConfig;
        $this->storeFacade = $storeFacade;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodsTransfer = $this->findPaymentMethods($quoteTransfer);
        $paymentMethodsTransfer = $this->applyFilterPlugins($paymentMethodsTransfer, $quoteTransfer);

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function findPaymentMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        $paymentMethodsFromPersistence = $this->paymentRepository->getActivePaymentMethodsForStore(
            $this->getIdStoreFromQuote($quoteTransfer)
        );

        return $this->collectPaymentMethodsByStateMachineMapping($paymentMethodsFromPersistence);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectPaymentMethodsByStateMachineMapping(
        PaymentMethodsTransfer $paymentMethodsFromPersistence
    ): PaymentMethodsTransfer {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentStateMachineMappings = array_keys($this->paymentConfig->getPaymentStatemachineMappings());
        $persistentMethodNames = $this->getPersistentPaymentMethodKeys($paymentMethodsFromPersistence);

        $paymentMethodsTransfer = $this->collectPaymentMethodsFromPersistence(
            $paymentMethodsTransfer,
            $paymentMethodsFromPersistence
        );

        $infrastructuralMethodNames = array_diff($paymentStateMachineMappings, $persistentMethodNames);

        return $this->collectInfrastructuralPaymentMethods(
            $infrastructuralMethodNames,
            $paymentMethodsTransfer
        );
    }

    /**
     * @param string[] $infrastructuralMethodNames
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectInfrastructuralPaymentMethods(
        array $infrastructuralMethodNames,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        foreach ($infrastructuralMethodNames as $methodKey) {
            $infrastructurePaymentMethod = $this->createPaymentMethodTransfer($methodKey);
            $paymentMethodsTransfer->addMethod($infrastructurePaymentMethod);
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectPaymentMethodsFromPersistence(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        PaymentMethodsTransfer $paymentMethodsFromPersistence
    ): PaymentMethodsTransfer {
        foreach ($paymentMethodsFromPersistence->getMethods() as $paymentMethodTransfer) {
            if ($this->isPaymentMethodAvailableForStore($paymentMethodTransfer)) {
                $paymentMethodsTransfer->addMethod($paymentMethodTransfer);
            }
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodAvailableForStore(
        PaymentMethodTransfer $paymentMethodTransfer
    ): bool {
        $paymentMethodTransfer->requireStoreRelation();
        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation();

        return $paymentMethodTransfer->getIsActive() && $storeRelationTransfer !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     *
     * @return string[]
     */
    protected function getPersistentPaymentMethodKeys(PaymentMethodsTransfer $paymentMethodsFromPersistence): array
    {
        $persistentMethodNames = [];

        foreach ($paymentMethodsFromPersistence->getMethods() as $paymentMethodTransfer) {
            $persistentMethodNames[] = $paymentMethodTransfer->getMethodName();
        }

        return $persistentMethodNames;
    }

    /**
     * @param string $methodKey
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function createPaymentMethodTransfer($methodKey)
    {
        $paymentMethodTransfer = new PaymentMethodTransfer();
        $paymentMethodTransfer->setMethodName($methodKey);

        return $paymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function applyFilterPlugins(PaymentMethodsTransfer $paymentMethodsTransfer, $quoteTransfer)
    {
        foreach ($this->paymentMethodFilterPlugins as $paymentMethodFilterPlugin) {
            $paymentMethodsTransfer = $paymentMethodFilterPlugin->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getIdStoreFromQuote(QuoteTransfer $quoteTransfer): int
    {
        $quoteTransfer->requireStore();
        $storeTransfer = $quoteTransfer->getStore();

        if ($storeTransfer->getIdStore() === null) {
            $storeTransfer->requireName();
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        }

        return $storeTransfer->getIdStore();
    }
}
