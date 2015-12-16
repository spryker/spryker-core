<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Spryker\Zed\ItemGrouper\Business\Model\Group;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ItemGrouper\ItemGrouperConfig;

/**
 * @method ItemGrouperConfig getConfig()
 */
class ItemGrouperDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @param bool $regroupAllItemCollection
     *
     * @return Model\Group
     */
    public function createGrouper($regroupAllItemCollection = false)
    {
        return new Group($this->getConfig()->getGroupingThreshold(), $regroupAllItemCollection);
    }

}
