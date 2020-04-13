<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Updater;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantUpdater implements MerchantUpdaterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_MERCHANT_NOT_FOUND = 'Merchant is not found.';
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
     * @var \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[]
     */
    protected $merchantPostUpdatePlugins;

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface
     */
    protected $merchantUrlSaver;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface $merchantStatusValidator
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[] $merchantPostUpdatePlugins
     * @param \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface $merchantUrlSaver
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantRepositoryInterface $merchantRepository,
        MerchantStatusValidatorInterface $merchantStatusValidator,
        array $merchantPostUpdatePlugins,
        MerchantUrlSaverInterface $merchantUrlSaver
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantRepository = $merchantRepository;
        $this->merchantStatusValidator = $merchantStatusValidator;
        $this->merchantPostUpdatePlugins = $merchantPostUpdatePlugins;
        $this->merchantUrlSaver = $merchantUrlSaver;
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

        if (!$this->merchantStatusValidator->isMerchantStatusTransitionValid($existingMerchantTransfer->getStatus(), $merchantTransfer->getStatus())) {
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
        $this->updateMerchantStores($merchantTransfer);
        $merchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        $merchantTransfer = $this->executeMerchantPostUpdatePlugins($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function updateMerchantStores(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer->requireStoreRelation();

        $currentStoreRelationTransfer = $this->merchantRepository->getMerchantStoresByIdMerchant($merchantTransfer->getIdMerchant());

        if (count($currentStoreRelationTransfer->getIdStores()) === 0) {
            foreach ($merchantTransfer->getStoreRelation()->getIdStores() as $idStore) {
                $this->merchantEntityManager->createMerchantStore($merchantTransfer, (new StoreTransfer())->setIdStore($idStore));
            }

            return;
        }

        $currentStoreIds = $currentStoreRelationTransfer->getIdStores();
        $requestedStoreIds = $merchantTransfer->getStoreRelation()->getIdStores();

        $storeIdsToCreate = array_diff($requestedStoreIds, $currentStoreIds);
        $storesIdsToDelete = array_diff($currentStoreIds, $requestedStoreIds);

        foreach ($storeIdsToCreate as $idStore) {
            $this->merchantEntityManager->createMerchantStore($merchantTransfer, (new StoreTransfer())->setIdStore($idStore));
        }

        foreach ($storesIdsToDelete as $idStore) {
            $this->merchantEntityManager->deleteMerchantStore($merchantTransfer, (new StoreTransfer())->setIdStore($idStore));
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
                throw (new MerchantNotSavedException($merchantResponseTransfer->getErrors()));
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
            ->requireEmail();
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
}
