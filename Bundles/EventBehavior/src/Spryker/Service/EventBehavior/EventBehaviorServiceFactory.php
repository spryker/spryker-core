<?php

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
