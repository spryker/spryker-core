<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ItemGrouperBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ItemGrouper\Business\Model;
use SprykerFeature\Zed\ItemGrouper\ItemGrouperConfig;

/**
 * @method ItemGrouperBusiness getFactory()
 * @method ItemGrouperConfig getConfig()
 */
class ItemGrouperDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return Model\Group
     */
    public function createGrouper()
    {
        return $this->getFactory()->createModelGroup($this->getConfig()->getGroupingThreshold());
    }
}
