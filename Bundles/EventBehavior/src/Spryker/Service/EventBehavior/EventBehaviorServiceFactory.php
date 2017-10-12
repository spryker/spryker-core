<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\EventBehavior;

use Spryker\Service\EventBehavior\Model\ArrayFilter;
use Spryker\Service\EventBehavior\Model\EventEntity;
use Spryker\Service\Kernel\AbstractServiceFactory;

class EventBehaviorServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\EventBehavior\Model\ArrayFilterInterface
     */
    public function createArrayFilter()
    {
        return new ArrayFilter();
    }

    /**
     * @return \Spryker\Service\EventBehavior\Model\EventEntityInterface
     */
    public function createEventEntity()
    {
        return new EventEntity();
    }
}
