<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Registry;

use ArrayAccess;
use IteratorAggregate;

interface PublisherEventRegistryInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * @param string $eventName
     * @param string $publisherPluginClassName
     *
     * @return $this
     */
    public function register(string $eventName, string $publisherPluginClassName);
}
