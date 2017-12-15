<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelPageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\ProductLabelPageSearch\Communication\Plugin\Event\Listener\ProductLabelPageSearchListener;

/**
 * @method \Spryker\Zed\ProductLabelPageSearch\Communication\ProductLabelPageSearchCommunicationFactory getFactory()
 */
class ProductLabelPageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE, new ProductLabelPageSearchListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE, new ProductLabelPageSearchListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE, new ProductLabelPageSearchListener());

        return $eventCollection;
    }

}
