<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Updater;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\EventBehavior\EventBehaviorConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\Business\Trigger\MerchantEventTriggerInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantUpdater implements MerchantUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_NOT_FOUND = 'Merchant is not found.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_STATUS_TRANSITION_NOT_VALID = 'Merchant status transition is not valid.';

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $merchantEntityManager;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface
     */
    protected $merchantStatusValidator;

    /**
     * @var array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface>
     */
    protected $merchantPostUpdatePlugins;

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface
     */
    protected $merchantUrlSaver;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\Merchant\Business\Trigger\MerchantEventTriggerInterface
     */
    protected $merchantEventTrigger;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface $merchantStatusValidator
     * @param array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface> $merchantPostUpdatePlugins
     * @param \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface $merchantUrlSaver
     * @param \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Merchant\Business\Trigger\MerchantEventTriggerInterface $merchantEventTrigger
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantRepositoryInterface $merchantRepository,
        MerchantStatusValidatorInterface $merchantStatusValidator,
        array $merchantPostUpdatePlugins,
        MerchantUrlSaverInterface $merchantUrlSaver,
        MerchantToEventFacadeInterface $eventFacade,
        MerchantEventTriggerInterface $merchantEventTrigger
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantRepository = $merchantRepository;
        $this->merchantStatusValidator = $merchantStatusValidator;
        $this->merchantPostUpdatePlugins = $merchantPostUpdatePlugins;
        $this->merchantUrlSaver = $merchantUrlSaver;
        $this->eventFacade = $eventFacade;
        $this->merchantEventTrigger = $merchantEventTrigger;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function update(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $this->assertDefaultMerchantRequirements($merchantTransfer);
        $merchantTransfer->requireIdMerchant();

        $merchantResponseTransfer = $this->createMerchantResponseTransfer();

        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setIdMerchant($merchantTransfer->getIdMerchant());

        $existingMerchantTransfer = $this->merchantRepository->findOne($merchantCriteriaTransfer);
        if ($existingMerchantTransfer === null) {
            $merchantResponseTransfer = $this->addMerchantError($merchantResponseTransfer, static::ERROR_MESSAGE_MERCHANT_NOT_FOUND);

            return $merchantResponseTransfer;
        }
        /** @var string $existingMerchantStatus */
        $existingMerchantStatus = $existingMerchantTransfer->getStatus();

        /** @var string $merchantStatus */
        $merchantStatus = $merchantTransfer->getStatus();

        if (!$this->merchantStatusValidator->isMerchantStatusTransitionValid($existingMerchantStatus, $merchantStatus)) {
            $merchantResponseTransfer = $this->addMerchantError($merchantResponseTransfer, static::ERROR_MESSAGE_MERCHANT_STATUS_TRANSITION_NOT_VALID);

            return $merchantResponseTransfer;
        }

        try {
            $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
                return $this->executeUpdateTransaction($merchantTransfer);
            });
        } catch (MerchantNotSavedException $merchantNotSavedException) {
            return $merchantResponseTransfer
                ->setIsSuccess(false)
                ->setErrors($merchantNotSavedException->getErrors())
                ->setMerchant($merchantTransfer);
        }

        $merchantResponseTransfer = $merchantResponseTransfer
            ->setIsSuccess(true)
            ->setMerchant($merchantTransfer);

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeUpdateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer = $this->merchantUrlSaver->saveMerchantUrls($merchantTransfer);
        EventBehaviorConfig::disableEvent(); // Merchant event will be triggered once after all merchant changes to avoid event duplication.
        $merchantTransfer = $this->updateMerchantStores($merchantTransfer);
        $merchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        EventBehaviorConfig::enableEvent();
        $merchantTransfer = $this->executeMerchantPostUpdatePlugins($merchantTransfer);

        $this->merchantEventTrigger->triggerMerchantUpdatedEvent($merchantTransfer);

        $this->triggerPublishEvent($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function updateMerchantStores(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        /** @var int $idMerchant */
        $idMerchant = $merchantTransfer->getIdMerchant();

        $merchantStoreRelationTransferMap = $this->merchantRepository->getMerchantStoreRelationMapByMerchantIds([$idMerchant]);
        $currentStoreRelationTransfer = $merchantStoreRelationTransferMap[$merchantTransfer->getIdMerchant()];

        /** @var \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer */
        $storeRelationTransfer = $merchantTransfer->getStoreRelation();

        if (!$currentStoreRelationTransfer->getIdStores()) {
            return $this->createMerchantStores(
                $merchantTransfer,
                $storeRelationTransfer->getIdStores(),
            );
        }

        $currentStoreIds = $currentStoreRelationTransfer->getIdStores();
        $requestedStoreIds = $storeRelationTransfer->getIdStores();

        $merchantTransfer = $this->createMerchantStores($merchantTransfer, array_diff($requestedStoreIds, $currentStoreIds));
        $this->deleteMerchantStores($merchantTransfer, array_diff($currentStoreIds, $requestedStoreIds));

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param array<int> $storeIds
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchantStores(MerchantTransfer $merchantTransfer, array $storeIds): MerchantTransfer
    {
        /** @var \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer */
        $storeRelationTransfer = $merchantTransfer->getStoreRelation();

        foreach ($storeIds as $idStore) {
            $storeTransfer = $this->merchantEntityManager->createMerchantStore($merchantTransfer, $idStore);
            $storeRelationTransfer->addStores($storeTransfer);
        }

        $storeRelationTransfer
            ->setIdEntity($merchantTransfer->getIdMerchant())
            ->setIdStores($storeIds);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param array<int> $storeIds
     *
     * @return void
     */
    protected function deleteMerchantStores(MerchantTransfer $merchantTransfer, array $storeIds): void
    {
        foreach ($storeIds as $idStore) {
            $this->merchantEntityManager->deleteMerchantStore($merchantTransfer, $idStore);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeMerchantPostUpdatePlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantPostUpdatePlugins as $merchantPostUpdatePlugin) {
            $merchantResponseTransfer = $merchantPostUpdatePlugin->postUpdate($merchantTransfer);
            if (!$merchantResponseTransfer->getIsSuccess()) {
                throw new MerchantNotSavedException($merchantResponseTransfer->getErrors());
            }
        }

        return $merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(): MerchantResponseTransfer
    {
        return (new MerchantResponseTransfer())
            ->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function assertDefaultMerchantRequirements(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer
            ->requireName()
            ->requireEmail()
            ->requireStoreRelation();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantResponseTransfer $merchantResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function addMerchantError(MerchantResponseTransfer $merchantResponseTransfer, string $message): MerchantResponseTransfer
    {
        $merchantResponseTransfer->addError((new MerchantErrorTransfer())->setMessage($message));

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function triggerPublishEvent(MerchantTransfer $merchantTransfer): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($merchantTransfer->getIdMerchant());

        $this->eventFacade->trigger(MerchantEvents::MERCHANT_PUBLISH, $eventEntityTransfer);
    }
}
