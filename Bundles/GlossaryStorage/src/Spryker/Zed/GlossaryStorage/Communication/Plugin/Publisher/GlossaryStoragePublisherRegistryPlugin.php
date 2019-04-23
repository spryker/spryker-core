<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher;

use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryDeletePublisherPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryWritePublisherPlugin;
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
    public function getPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface
    {
        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_PUBLISH, new GlossaryWritePublisherPlugin());
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_CREATE, new GlossaryWritePublisherPlugin());
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE, new GlossaryWritePublisherPlugin());

        $publisherEventRegistry->register(GlossaryEvents::GLOSSARY_KEY_UNPUBLISH, new GlossaryDeletePublisherPlugin());
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_DELETE, new GlossaryDeletePublisherPlugin());

        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE, new GlossaryWritePublisherPlugin());
        $publisherEventRegistry->register(GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE, new GlossaryWritePublisherPlugin());

        return $publisherEventRegistry;
    }
}
