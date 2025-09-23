<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductLabelSearch\Communication\Plugin\Publisher\ProductLabelStore\ProductLabelStoreWritePublisherPlugin} instead.
 *
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelSearch\Business\ProductLabelSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface getRepository()
 */
class ProductLabelProductAbstractSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $hydrateEventsResponseTransfer = $this->getFactory()->getEventBehaviorFacade()->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers))
                ->setForeignKeyName(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT),
        );

        $this->getFactory()->getProductPageSearchFacade()->publishWithTimestamp($hydrateEventsResponseTransfer->getForeignKeyTimestampMap());
    }
}
