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
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 */
class ProductPackagingUnitTypePublishStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productPackagingTypeIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $productAbstractIds = $this->getFacade()->findProductAbstractIdsByProductPackagingUnitTypeIds($productPackagingTypeIds);

        $publishEvents = $this->getPublishEvents();

        if (in_array($eventName, $publishEvents)) {
            $this->getFacade()->publishProductAbstractPackaging($productAbstractIds);

            return;
        }

        $this->getFacade()->unpublishProductAbstractPackaging($productAbstractIds);
    }

    /**
     * @return string[]
     */
    protected function getPublishEvents(): array
    {
        return [
            ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_CREATE,
            ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_UNIT_TYPE_UPDATE,
        ];
    }
}
