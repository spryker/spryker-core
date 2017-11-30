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
        $eventCollection
            ->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, new GlossaryKeyStorageListener())
            ->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, new GlossaryKeyStorageListener())
            ->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, new GlossaryKeyStorageListener())
            ->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, new GlossaryTranslationStorageListener())
            ->addListenerQueued(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, new GlossaryTranslationStorageListener());

        return $eventCollection;
    }

}
