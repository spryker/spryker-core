<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublisherExtension\Dependency;

use ArrayAccess;
use IteratorAggregate;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

interface PublisherEventRegistryInterface extends ArrayAccess, IteratorAggregate
{

    /**
     * @param string $eventName
     * @param PublisherPluginInterface $publisherPlugin
     *
     * @return $this
     */
    public function register(string $eventName, PublisherPluginInterface $publisherPlugin);
}
