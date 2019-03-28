<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;


interface PublishingRegistryCollectionInterface extends \ArrayAccess, \IteratorAggregate
{

    /**
     * @param PublishingRegistryInterface $publishingRegistry
     *
     * @return mixed
     */
    public function add(PublishingRegistryInterface $publishingRegistry);
}
