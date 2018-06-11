<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPackagingUnit\Dependency\ProductPackagingUnitEvents;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 */
class ProductPackagingUnitPublishStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        if ($eventName === ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_DELETE
            || $eventName === ProductPackagingUnitEvents::PRODUCT_PACKAGING_UNIT_UNPUBLISH
            || $eventName === ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_DELETE
            || $eventName === ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_AMOUNT_DELETE
            || $eventName === ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_DELETE
        ) {
            $this->getFacade()->unpublishProductAbstractPackaging($productAbstractIds);

            return;
        }

        $this->getFacade()->publishProductAbstractPackaging($productAbstractIds);
    }
}
