<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductListStorage\Communication\ProductListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListStorage\Business\ProductListStorageFacadeInterface getFacade()
 */
class ProductAbstractStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->preventTransaction();

        $this->getFacade()->publishProductAbstract($this->getProductAbstractIds($eventTransfers, $eventName));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return int[]
     */
    protected function getProductAbstractIds(array $eventTransfers, string $eventName): array
    {
        if ($eventName === ProductEvents::PRODUCT_CONCRETE_PUBLISH) {
            $productConcreteIds = $this->getFactory()
                ->getEventBehaviorFacade()
                ->getEventTransferIds($eventTransfers);

            return $this->getFacade()->findProductAbstractIdsByProductConcreteIds($productConcreteIds);
        }

        return $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);
    }
}
