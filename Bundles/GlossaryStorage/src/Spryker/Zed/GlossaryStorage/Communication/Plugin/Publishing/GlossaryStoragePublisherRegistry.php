<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryKeyPublisher;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryKeyUnpublisher;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryTranslationPublisher;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublishingExtension\Dependency\PublisherCollectionInterface;
use Spryker\Zed\PublishingExtension\Dependency\PublisherRegistryInterface;

/**
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryStoragePublisherRegistry extends AbstractPlugin implements PublisherRegistryInterface
{

    /**
     * @param PublisherCollectionInterface $publisherCollection
     *
     * @return PublisherCollectionInterface
     */
    public function getRegisteredPublishers(PublisherCollectionInterface $publisherCollection)
    {
        $publisherCollection->addPublisher(GlossaryEvents::GLOSSARY_KEY_PUBLISH, new GlossaryKeyPublisher());
        $publisherCollection->addPublisher(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, new GlossaryKeyPublisher());
        $publisherCollection->addPublisher(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, new GlossaryKeyPublisher());

        $publisherCollection->addPublisher(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, new GlossaryKeyUnpublisher());
        $publisherCollection->addPublisher(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, new GlossaryKeyUnpublisher());

        $publisherCollection->addPublisher(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, new GlossaryTranslationPublisher());
        $publisherCollection->addPublisher(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, new GlossaryTranslationPublisher());

        return $publisherCollection;
    }
}
