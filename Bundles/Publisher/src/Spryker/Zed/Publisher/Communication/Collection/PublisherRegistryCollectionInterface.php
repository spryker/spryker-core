<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Dependency;


use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface;

interface PublisherRegistryCollectionInterface extends \ArrayAccess, \IteratorAggregate
{

    /**
     * @param PublisherRegistryPluginInterface $publisherRegistry
     *
     * @return mixed
     */
    public function add(PublisherRegistryPluginInterface $publisherRegistry);
}
