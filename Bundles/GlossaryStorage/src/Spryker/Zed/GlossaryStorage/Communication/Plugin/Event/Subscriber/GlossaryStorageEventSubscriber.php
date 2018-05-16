<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryKeyStorageListener;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryTranslationStorageListener;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 */
class GlossaryStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addGlossaryKeyPublishStorageListener($eventCollection);
        $this->addGlossaryKeyUnpublishStorageListener($eventCollection);
        $this->addGlossaryKeyCreateStorageListener($eventCollection);
        $this->addGlossaryKeyUpdateStorageListener($eventCollection);
        $this->addGlossaryKeyDeleteStorageListener($eventCollection);
        $this->addGlossaryTranslationCreateStorageListener($eventCollection);
        $this->addGlossaryTranslationUpdateStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryKeyPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::GLOSSARY_KEY_PUBLISH, new GlossaryKeyStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryKeyUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, new GlossaryKeyStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryKeyCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, new GlossaryKeyStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryKeyUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, new GlossaryKeyStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryKeyDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, new GlossaryKeyStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryTranslationCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, new GlossaryTranslationStorageListener());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addGlossaryTranslationUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, new GlossaryTranslationStorageListener());
        ;
    }
}
