<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher;

use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryDeletePublisherPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryWritePublisherPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryTranslation\GlossaryWritePublisherPlugin as GlossaryTranslationWritePublisherPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;
use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

/**
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryStoragePublisherRegistryPlugin extends AbstractPlugin implements PublisherRegistryPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    public function expandPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface
    {
        $this->registerGlossaryWritePublisherPluginWithGlossaryKeyPublishEvent($publisherEventRegistry);
        $this->registerGlossaryWritePublisherPluginWithGlossaryKeyCreateEvent($publisherEventRegistry);
        $this->registerGlossaryWritePublisherPluginWithGlossaryKeyUpdateEvent($publisherEventRegistry);
        $this->registerGlossaryDeletePublisherPluginWithGlossaryKeyUnpublishEvent($publisherEventRegistry);
        $this->registerGlossaryDeletePublisherPluginWithGlossaryKeyDeleteEvent($publisherEventRegistry);
        $this->registerGlossaryTranslationWritePublisherPluginWithGlossaryTranslationCreateEvent($publisherEventRegistry);
        $this->registerGlossaryTranslationWritePublisherPluginWithGlossaryTranslationUpdateEvent($publisherEventRegistry);

        return $publisherEventRegistry;
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryWritePublisherPluginWithGlossaryKeyPublishEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_PUBLISH, GlossaryWritePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryWritePublisherPluginWithGlossaryKeyCreateEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, GlossaryWritePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryWritePublisherPluginWithGlossaryKeyUpdateEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, GlossaryWritePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryDeletePublisherPluginWithGlossaryKeyUnpublishEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, GlossaryDeletePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryDeletePublisherPluginWithGlossaryKeyDeleteEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, GlossaryDeletePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryTranslationWritePublisherPluginWithGlossaryTranslationCreateEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, GlossaryTranslationWritePublisherPlugin::class);
    }

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return void
     */
    protected function registerGlossaryTranslationWritePublisherPluginWithGlossaryTranslationUpdateEvent(PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, GlossaryTranslationWritePublisherPlugin::class);
    }
}
