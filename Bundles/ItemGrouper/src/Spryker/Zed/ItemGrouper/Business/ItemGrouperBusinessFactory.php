<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Spryker\Zed\ItemGrouper\Business\Model\Group;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ItemGrouper\ItemGrouperConfig getConfig()
 */
class ItemGrouperBusinessFactory extends AbstractBusinessFactory
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
