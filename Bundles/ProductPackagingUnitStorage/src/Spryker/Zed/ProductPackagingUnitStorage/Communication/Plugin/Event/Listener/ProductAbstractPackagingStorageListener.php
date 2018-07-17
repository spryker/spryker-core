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
 */
class ProductAbstractPackagingStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $idProductAbstracts = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $unpublishEvents = $this->getUnpublishEvents();

        if (in_array($eventName, $unpublishEvents)) {
            $this->getFacade()->unpublishProductAbstractPackaging($idProductAbstracts);

            return;
        }

        $this->getFacade()->publishProductAbstractPackaging($idProductAbstracts);
    }

    /**
     * @return string[]
     */
    protected function getUnpublishEvents(): array
    {
        return [
            ProductPackagingUnitEvents::PRODUCT_ABSTRACT_PACKAGING_PUBLISH,
            ProductPackagingUnitEvents::PRODUCT_ABSTRACT_PACKAGING_UNPUBLISH,
        ];
    }
}
