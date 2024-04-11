<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface $eventFacade
     */
    public function __construct(ProductToEventInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function triggerProductAbstractUpdateEvents(array $productAbstractIds): void
    {
        $eventEntityTransfers = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $eventEntityTransfers[] = (new EventEntityTransfer())
                ->setId($idProductAbstract);
        }

        $this->eventFacade->triggerBulk(ProductEvents::PRODUCT_ABSTRACT_UPDATE, $eventEntityTransfers);
    }

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function triggerProductUpdateEvents(array $productIds): void
    {
        $eventEntityTransfers = [];

        foreach ($productIds as $idProduct) {
            $eventEntityTransfers[] = (new EventEntityTransfer())
                ->setId($idProduct);
        }

        $this->eventFacade->triggerBulk(ProductEvents::PRODUCT_CONCRETE_UPDATE, $eventEntityTransfers);
    }
}
