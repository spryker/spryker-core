<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Creator;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantCreator implements MerchantCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $merchantEntityManager;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $merchantConfig;

    /**
     * @var \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface[]
     */
    protected $merchantPostCreatePlugins;

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface
     */
    protected $merchantUrlSaver;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\MerchantConfig $merchantConfig
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface[] $merchantPostCreatePlugins
     * @param \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface $merchantUrlSaver
     * @param \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface $eventFacade
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantConfig $merchantConfig,
        array $merchantPostCreatePlugins,
        MerchantUrlSaverInterface $merchantUrlSaver,
        MerchantToEventFacadeInterface $eventFacade
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantConfig = $merchantConfig;
        $this->merchantPostCreatePlugins = $merchantPostCreatePlugins;
        $this->merchantUrlSaver = $merchantUrlSaver;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $this->assertDefaultMerchantRequirements($merchantTransfer);

        $merchantTransfer->setStatus($this->merchantConfig->getDefaultMerchantStatus());
        $merchantResponseTransfer = $this->createMerchantResponseTransfer();

        try {
            $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
                return $this->executeCreateTransaction($merchantTransfer);
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
    protected function executeCreateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $storeRelationTransfer = $merchantTransfer->getStoreRelation();
        $urlTransfers = $merchantTransfer->getUrlCollection();

        $merchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        $merchantTransfer = $this->createMerchantStores($merchantTransfer->setStoreRelation($storeRelationTransfer));
        $merchantTransfer = $this->merchantUrlSaver->saveMerchantUrls($merchantTransfer->setUrlCollection($urlTransfers));
        $merchantTransfer = $this->executeMerchantPostCreatePlugins($merchantTransfer);

        $this->triggerPublishEvent($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchantStores(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        /** @var \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer */
        $storeRelationTransfer = $merchantTransfer->getStoreRelation();

        foreach ($storeRelationTransfer->getIdStores() as $idStore) {
            $storeTransfer = $this->merchantEntityManager->createMerchantStore($merchantTransfer, $idStore);
            $storeRelationTransfer->addStores($storeTransfer);
        }
        $storeRelationTransfer->setIdEntity($merchantTransfer->getIdMerchant());

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
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeMerchantPostCreatePlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantPostCreatePlugins as $merchantPostCreatePlugin) {
            $merchantResponseTransfer = $merchantPostCreatePlugin->postCreate($merchantTransfer);
            if (!$merchantResponseTransfer->getIsSuccess()) {
                throw (new MerchantNotSavedException($merchantResponseTransfer->getErrors()));
            }
        }

        return $merchantTransfer;
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
