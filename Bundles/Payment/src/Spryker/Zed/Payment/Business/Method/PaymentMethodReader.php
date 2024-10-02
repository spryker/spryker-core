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
     * @var array<\Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface>
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
     * @param array<\Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface> $paymentMethodFilterPlugins
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
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
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
        $paymentMethodsFromPersistence = $this->paymentRepository->getPaymentMethodsWithStoreRelation();

        return $this->collectPaymentMethodsByStateMachineMapping(
            $paymentMethodsFromPersistence,
            $this->getIdStoreFromQuote($quoteTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectPaymentMethodsByStateMachineMapping(
        PaymentMethodsTransfer $paymentMethodsFromPersistence,
        int $idStore
    ): PaymentMethodsTransfer {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentStateMachineMappings = array_keys($this->paymentConfig->getPaymentStatemachineMappings());
        $persistentMethodNames = $this->getPersistentPaymentMethodKeys($paymentMethodsFromPersistence);

        $paymentMethodsTransfer = $this->collectPaymentMethodsFromPersistence(
            $paymentMethodsTransfer,
            $paymentMethodsFromPersistence,
            $idStore,
        );

        $infrastructuralMethodNames = array_diff($paymentStateMachineMappings, $persistentMethodNames);

        return $this->collectInfrastructuralPaymentMethods(
            $infrastructuralMethodNames,
            $paymentMethodsTransfer,
        );
    }

    /**
     * @param array<string> $infrastructuralMethodNames
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectInfrastructuralPaymentMethods(
        array $infrastructuralMethodNames,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        foreach ($infrastructuralMethodNames as $methodKey) {
            $infrastructurePaymentMethod = (new PaymentMethodTransfer())
                ->setMethodName($methodKey)
                ->setPaymentMethodKey($methodKey);

            $paymentMethodsTransfer->addMethod($infrastructurePaymentMethod);
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function collectPaymentMethodsFromPersistence(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        PaymentMethodsTransfer $paymentMethodsFromPersistence,
        int $idStore
    ): PaymentMethodsTransfer {
        foreach ($paymentMethodsFromPersistence->getMethods() as $paymentMethodTransfer) {
            if ($this->isPaymentMethodAvailableForStore($paymentMethodTransfer, $idStore)) {
                $paymentMethodsTransfer->addMethod($paymentMethodTransfer);
            }
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsFromPersistence
     *
     * @return array<string>
     */
    protected function getPersistentPaymentMethodKeys(PaymentMethodsTransfer $paymentMethodsFromPersistence): array
    {
        $persistentMethodNames = [];

        foreach ($paymentMethodsFromPersistence->getMethods() as $paymentMethodTransfer) {
            $persistentMethodNames[] = $paymentMethodTransfer->getPaymentMethodKey();
        }

        return $persistentMethodNames;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param int $idStore
     *
     * @return bool
     */
    protected function isPaymentMethodAvailableForStore(
        PaymentMethodTransfer $paymentMethodTransfer,
        int $idStore
    ): bool {
        $paymentMethodTransfer->requireStoreRelation();
        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation();

        return $paymentMethodTransfer->getIsActive() && in_array($idStore, $storeRelationTransfer->getIdStores());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function applyFilterPlugins(PaymentMethodsTransfer $paymentMethodsTransfer, $quoteTransfer): PaymentMethodsTransfer
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
