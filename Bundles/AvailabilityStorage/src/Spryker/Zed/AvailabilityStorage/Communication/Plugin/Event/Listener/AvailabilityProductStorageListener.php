<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 */
class AvailabilityProductStorageListener extends AbstractAvailabilityStorageListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param bool $isSendingToQueue
     */
    public function __construct($isSendingToQueue = true)
    {
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();

        $abstractProductSkus = [];
        $abstractProductIds = [];

        if ($eventName !== ProductEvents::ENTITY_SPY_PRODUCT_UPDATE) {
            return;
        }

        $spyAbstractProducts = $this->findProductAbstracts($eventTransfers);
        foreach ($spyAbstractProducts as $spyAbstractProduct) {
            if ($this->hasActiveProducts($spyAbstractProduct)) {
                $abstractProductSkus[] = $spyAbstractProduct->getSku();
            } else {
                $abstractProductIds[] = $spyAbstractProduct->getIdProductAbstract();
            }
        }

        $abstractAvailabilityIds = $this->findAvailabilityAbstractBySkus($abstractProductSkus);
        $this->publish($abstractAvailabilityIds, $this->isSendingToQueue);
        $this->unpublishByAbstractProductIds($abstractProductIds);
    }

    /**
     * @param array $idAbstractProducts
     * @param bool $sendingToQueue
     *
     * @return void
     */
    protected function unpublishByAbstractProductIds(array $idAbstractProducts, $sendingToQueue = true)
    {
        $spyAvailabilityStorageEntities = $this->findAvailabilityStorageEntitiesByAbstractProductIds($idAbstractProducts);
        foreach ($spyAvailabilityStorageEntities as $spyAvailabilityStorageEntity) {
            $spyAvailabilityStorageEntity->setIsSendingToQueue($sendingToQueue);
            $spyAvailabilityStorageEntity->delete();
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyAbstractProducts
     *
     * @return bool
     */
    protected function hasActiveProducts(SpyProductAbstract $spyAbstractProducts)
    {
        foreach ($spyAbstractProducts->getSpyProducts() as $spyProduct) {
            if ($spyProduct->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $eventTransfers
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function findProductAbstracts(array $eventTransfers)
    {
        $abstractProductIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        return $this->getQueryContainer()->queryProductAbstractWithProductByAbstractProductIds($abstractProductIds)->find()->getData();
    }

    /**
     * @param array $abstractProductSkus
     *
     * @return mixed|\Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract[]
     */
    protected function findAvailabilityAbstractBySkus(array $abstractProductSkus)
    {
        return $this->getQueryContainer()->queryAvailabilityAbstractByAbstractProductSkus($abstractProductSkus)->find()->getData();
    }

    /**
     * @param array $abstractProductIds
     *
     * @return array
     */
    protected function findAvailabilityStorageEntitiesByAbstractProductIds(array $abstractProductIds)
    {
        return $this->getQueryContainer()->queryAvailabilityStorageByProductAbstractIds($abstractProductIds)->find()->toKeyIndex(static::FK_PRODUCT_ABSTRACT);
    }
}
