<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @uses \Spryker\Shared\Product\ProductConfig::COLUMN_FK_PRODUCT_ABSTRACT
     *
     * @var string
     */
    public const COLUMN_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_CONCRETE_UPDATE
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_UPDATE = 'Product.product_concrete.update';

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface $eventFacade
     */
    public function __construct(ProductLabelToEventInterface $eventFacade)
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
                ->setForeignKeys([static::COLUMN_FK_PRODUCT_ABSTRACT => $idProductAbstract]);
        }

        $this->eventFacade->triggerBulk(static::PRODUCT_CONCRETE_UPDATE, $productUpdatedEvents);
    }
}
