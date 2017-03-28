<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;

interface EventSubscriberCollectionInterface
{

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $eventSubscriber
     *
     * @return void
     */
    public function add(EventSubscriberInterface $eventSubscriber);

}
