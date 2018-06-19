<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingLeadProductTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPackagingUnit\Dependency\ProductPackagingUnitEvents;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 */
class ProductPackagingLeadProductStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $idProductProductPackagingType = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductPackagingLeadProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        $publishEvents = $this->getPublishEvents();

        if (in_array($eventName, $publishEvents)) {
            $this->getFacade()->publishProductAbstractPackaging($idProductProductPackagingType);

            return;
        }

        $this->getFacade()->unpublishProductAbstractPackaging($idProductProductPackagingType);
    }

    /**
     * @return string[]
     */
    protected function getPublishEvents(): array
    {
        return [
            ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_CREATE,
            ProductPackagingUnitEvents::ENTITY_SPY_PRODUCT_PACKAGING_LEAD_PRODUCT_UPDATE,
        ];
    }
}
