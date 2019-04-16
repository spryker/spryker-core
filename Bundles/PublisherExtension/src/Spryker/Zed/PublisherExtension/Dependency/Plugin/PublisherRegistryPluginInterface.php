<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublisherExtension\Dependency\Plugin;

interface PublisherRegistryPluginInterface
{

    /**
     * @param PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return PublisherEventRegistryInterface
     */
    public function getPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry);
}
