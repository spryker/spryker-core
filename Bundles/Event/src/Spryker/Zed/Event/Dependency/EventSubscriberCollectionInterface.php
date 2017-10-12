<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use ArrayAccess;
use IteratorAggregate;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;

interface EventSubscriberCollectionInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $eventSubscriber
     *
     * @return void
     */
    public function add(EventSubscriberInterface $eventSubscriber);
}
