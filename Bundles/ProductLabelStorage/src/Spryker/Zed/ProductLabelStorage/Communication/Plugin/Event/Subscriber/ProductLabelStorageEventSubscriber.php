<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelPublishStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelStorageListener;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 */
class ProductLabelStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH, new ProductLabelPublishStorageListener())
            ->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_PRODUCT_ABSTRACT_UNPUBLISH, new ProductLabelPublishStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE, new ProductLabelStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE, new ProductLabelStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE, new ProductLabelStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_DICTIONARY_PUBLISH, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_DICTIONARY_UNPUBLISH, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_UPDATE, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_DELETE, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE, new ProductLabelDictionaryStorageListener())
            ->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE, new ProductLabelDictionaryStorageListener());

        return $eventCollection;
    }

}
