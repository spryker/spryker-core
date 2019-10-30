<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundlePageSearch\Communication\Plugin\Event\Listener\ConfigurableBundleTemplatePageSearchConfigurableBundleTemplatePublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Business\ConfigurableBundlePageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Communication\ConfigurableBundlePageSearchCommunicationFactory getFactory()
 */
class ConfigurableBundleTemplatePageSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addConfigurableBundleTemplateCreateListener($eventCollection)
            ->addConfigurableBundleTemplateUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConfigurableBundleTemplateCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_CREATE, new ConfigurableBundleTemplatePageSearchConfigurableBundleTemplatePublishListener());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addConfigurableBundleTemplateUpdateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ConfigurableBundleEvents::ENTITY_SPY_CONFIGURABLE_BUNDLE_TEMPLATE_UPDATE, new ConfigurableBundleTemplatePageSearchConfigurableBundleTemplatePublishListener());

        return $this;
    }
}
