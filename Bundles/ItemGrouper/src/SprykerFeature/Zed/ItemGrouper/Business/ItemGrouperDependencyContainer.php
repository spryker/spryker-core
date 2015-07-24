<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ItemGrouperBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ItemGrouper\Business\Model;

/**
 * @method ItemGrouperBusiness getFactory()
 */
class ItemGrouperDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return Model\Group
     */
    public function createGrouper()
    {
        return $this->getFactory()->createModelGroup();
    }
}
