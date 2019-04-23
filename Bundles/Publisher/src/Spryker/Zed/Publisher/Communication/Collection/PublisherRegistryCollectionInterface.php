<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication\Collection;

use ArrayAccess;
use IteratorAggregate;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;

interface PublisherRegistryCollectionInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface $publisherRegistry
     *
     * @return void
     */
    public function add(PublisherRegistryPluginInterface $publisherRegistry): void;
}
