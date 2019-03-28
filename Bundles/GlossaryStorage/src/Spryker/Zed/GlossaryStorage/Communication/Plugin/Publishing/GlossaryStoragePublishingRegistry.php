<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing;

use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryKey\GlossaryKeyPublisher;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryKey\GlossaryKeyUnpublisher;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publishing\GlossaryTranslation\GlossaryTranslationPublisher;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublishingExtension\Dependency\PublishingCollectionInterface;
use Spryker\Zed\PublishingExtension\Dependency\PublishingRegistryInterface;

/**
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryStoragePublishingRegistry extends AbstractPlugin implements PublishingRegistryInterface
{

    /**
     * @param PublishingCollectionInterface $publisherCollection
     *
     * @return PublishingCollectionInterface
     */
    public function getRegisteredPublishingCollection(PublishingCollectionInterface $publisherCollection)
    {
        $publisherCollection->addPublishingPlugin(GlossaryEvents::GLOSSARY_KEY_PUBLISH, new GlossaryKeyPublisher());
        $publisherCollection->addPublishingPlugin(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, new GlossaryKeyPublisher());
        $publisherCollection->addPublishingPlugin(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, new GlossaryKeyPublisher());

        $publisherCollection->addPublishingPlugin(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, new GlossaryKeyUnpublisher());
        $publisherCollection->addPublishingPlugin(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, new GlossaryKeyUnpublisher());

        $publisherCollection->addPublishingPlugin(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, new GlossaryTranslationPublisher());
        $publisherCollection->addPublishingPlugin(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, new GlossaryTranslationPublisher());

        return $publisherCollection;
    }
}
