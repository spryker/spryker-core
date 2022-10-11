<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Product\ProductConfig;
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
    public function triggerProductUpdateEvents(array $productAbstractIds): void
    {
        $productUpdatedEvents = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $productUpdatedEvents[] = (new EventEntityTransfer())
                ->setForeignKeys([ProductConfig::COLUMN_FK_PRODUCT_ABSTRACT => $idProductAbstract]);
        }

        $this->eventFacade->triggerBulk(ProductEvents::PRODUCT_CONCRETE_UPDATE, $productUpdatedEvents);
    }
}
