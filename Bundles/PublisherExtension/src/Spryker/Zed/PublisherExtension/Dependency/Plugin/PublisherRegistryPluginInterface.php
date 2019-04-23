<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublisherExtension\Dependency\Plugin;

use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

interface PublisherRegistryPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     *
     * @return \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    public function getPublisherEventRegistry(PublisherEventRegistryInterface $publisherEventRegistry): PublisherEventRegistryInterface;
}
