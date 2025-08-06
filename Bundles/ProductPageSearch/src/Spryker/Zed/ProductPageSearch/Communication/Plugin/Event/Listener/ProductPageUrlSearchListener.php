<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductPageUrlSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productAbstractIdTimestampMap = $this->getValidProductIds($eventEntityTransfers);
        if (!$productAbstractIdTimestampMap) {
            return;
        }

        $this->publish($productAbstractIdTimestampMap);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return array<int, int>
     */
    protected function getValidProductIds(array $eventTransfers)
    {
        $validEventTransfers = [];
        foreach ($eventTransfers as $eventTransfer) {
            if (
                in_array(SpyUrlTableMap::COL_URL, $eventTransfer->getModifiedColumns()) ||
                in_array(SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT, $eventTransfer->getModifiedColumns())
            ) {
                $validEventTransfers[] = $eventTransfer;
            }
        }

        return $this->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($validEventTransfers))
                ->setForeignKeyName(SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT),
        )->getForeignKeyTimestampMap();
    }
}
