<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Store\Business\Validator\StoreValidatorInterface;
use Spryker\Zed\Store\Persistence\StoreEntityManagerInterface;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class StoreWriter implements StoreWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_IS_NOT_FOUND = 'Store not found.';

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreRepositoryInterface
     */
    protected StoreRepositoryInterface $storeRepository;

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreEntityManagerInterface
     */
    protected StoreEntityManagerInterface $storeEntityManager;

    /**
     * @var \Spryker\Zed\Store\Business\Validator\StoreValidatorInterface
     */
    protected StoreValidatorInterface $storeValidator;

    /**
     * @var array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface>
     */
    protected array $storePostCreatePlugins;

    /**
     * @var array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface>
     */
    protected array $storePostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Store\Persistence\StoreRepositoryInterface $storeRepository
     * @param \Spryker\Zed\Store\Persistence\StoreEntityManagerInterface $storeEntityManager
     * @param \Spryker\Zed\Store\Business\Validator\StoreValidatorInterface $storeValidator
     * @param array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface> $storePostCreatePlugins
     * @param array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface> $storePostUpdatePlugins
     */
    public function __construct(
        StoreRepositoryInterface $storeRepository,
        StoreEntityManagerInterface $storeEntityManager,
        StoreValidatorInterface $storeValidator,
        array $storePostCreatePlugins,
        array $storePostUpdatePlugins
    ) {
        $this->storeRepository = $storeRepository;
        $this->storeEntityManager = $storeEntityManager;
        $this->storePostCreatePlugins = $storePostCreatePlugins;
        $this->storePostUpdatePlugins = $storePostUpdatePlugins;
        $this->storeValidator = $storeValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function createStore(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($storeTransfer) {
            return $this->executeCreateStoreTransaction($storeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStore(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($storeTransfer) {
            return $this->executeUpdateStoreTransaction($storeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function executeCreateStoreTransaction(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = $this->storeValidator->validateStoreNameIsUnique($storeTransfer);

        if (!$storeResponseTransfer->getIsSuccessful()) {
            return $storeResponseTransfer;
        }

        $storeResponseTransfer = $this->storeValidator->validatePreCreate($storeTransfer);

        if (!$storeResponseTransfer->getIsSuccessful()) {
            return $storeResponseTransfer;
        }

        $storeTransfer = $this->storeEntityManager
            ->createStore($storeTransfer);

        return $this->executePostCreatePlugins($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function executeUpdateStoreTransaction(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $store = $this->storeRepository->findStoreById($storeTransfer->getIdStoreOrFail());

        if ($store === null) {
            return (new StoreResponseTransfer())->setIsSuccessful(false)->addMessage((new MessageTransfer())->setValue(static::ERROR_MESSAGE_STORE_IS_NOT_FOUND));
        }

        $storeTransfer->setName($store->getNameOrFail());

        $storeResponseTransfer = $this->storeValidator->validatePreUpdate($storeTransfer);

        if (!$storeResponseTransfer->getIsSuccessful()) {
            return $storeResponseTransfer;
        }

        $storeTransfer = $this->storeEntityManager->updateStore($storeTransfer);

        return $this->executePostUpdatePlugins($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function executePostCreatePlugins(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);

        foreach ($this->storePostCreatePlugins as $storePostCreatePlugin) {
            $storeResponseTransfer = $this->mergeStoreResponseTransfers(
                $storeResponseTransfer,
                $storePostCreatePlugin->execute($storeTransfer),
            );
        }

        return $storeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function executePostUpdatePlugins(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);

        foreach ($this->storePostUpdatePlugins as $storePostUpdatePlugin) {
            $storeResponseTransfer = $this->mergeStoreResponseTransfers(
                $storeResponseTransfer,
                $storePostUpdatePlugin->execute($storeTransfer),
            );
        }

        return $storeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreResponseTransfer $sourceStoreResponseTransfer
     * @param \Generated\Shared\Transfer\StoreResponseTransfer $storeResponseTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function mergeStoreResponseTransfers(
        StoreResponseTransfer $sourceStoreResponseTransfer,
        StoreResponseTransfer $storeResponseTransfer
    ): StoreResponseTransfer {
        foreach ($storeResponseTransfer->getMessages() as $messageTransfer) {
            $sourceStoreResponseTransfer->addMessage($messageTransfer);
        }

        return $sourceStoreResponseTransfer
            ->setIsSuccessful($sourceStoreResponseTransfer->getIsSuccessful() && ($storeResponseTransfer->getIsSuccessful() ?? true));
    }
}
