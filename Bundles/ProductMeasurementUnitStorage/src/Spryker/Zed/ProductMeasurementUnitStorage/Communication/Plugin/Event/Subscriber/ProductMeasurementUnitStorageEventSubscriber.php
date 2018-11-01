<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents;
use Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Event\Listener\ProductConcreteMeasurementUnitStorageListener;
use Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Event\Listener\ProductMeasurementUnitStorageListener;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Communication\ProductMeasurementUnitStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageFacadeInterface getFacade()
 */
class ProductMeasurementUnitStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection
            ->addListenerQueued(ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH, new ProductMeasurementUnitStorageListener())
            ->addListenerQueued(ProductMeasurementUnitEvents::PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH, new ProductConcreteMeasurementUnitStorageListener());

        return $eventCollection;
    }
}
