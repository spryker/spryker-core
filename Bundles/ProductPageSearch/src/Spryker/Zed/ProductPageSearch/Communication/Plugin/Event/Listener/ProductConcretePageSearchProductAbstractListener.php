<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductAbstractListener extends AbstractProductConcretePageSearchListener
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $productConcreteIds = $this->getProductConcreteIds($productAbstractIds);

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE) {
            $this->unpublish($productConcreteIds);
        }

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE) {
            $this->publish($productConcreteIds);
        }
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function getProductConcreteIds(array $productAbstractIds)
    {
        $productConcreteIds = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $productConcreteIds = array_merge(
                $productConcreteIds,
                $this->getFactory()
                    ->getProductFacade()
                    ->findProductConcreteIdsByAbstractProductId($idProductAbstract)
            );
        }

        return $productConcreteIds;
    }
}
