<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\PriceProductOfferStorage\PriceProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOfferStorage\Communication\PriceProductOfferStorageCommunicationFactory getFactory()
 */
class ProductPublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use TransactionTrait;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $transfers = $this->getFactory()->getEventBehaviorFacade()->getEventTransfersByModifiedColumns($transfers, [SpyProductTableMap::COL_IS_ACTIVE]);
        $publishProductIds = [];
        $unpublishProductIds = [];
        foreach ($transfers as $transfer) {
            $originalValues = $transfer->getOriginalValues();
            if (!isset($originalValues[SpyProductTableMap::COL_IS_ACTIVE])) {
                return;
            }
            if (!$originalValues[SpyProductTableMap::COL_IS_ACTIVE]) {
                $publishProductIds[] = $transfer->getId();

                continue;
            }
            $unpublishProductIds[] = $transfer->getId();
        }
        if ($publishProductIds) {
            $this->getFacade()->publishByProductIds($publishProductIds);
        }

        if ($unpublishProductIds) {
            $this->getFacade()->unpublishByProductIds($unpublishProductIds);
        }
    }
}
