<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouper;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ItemGrouperConfig extends AbstractBundleConfig
{

    /**
     * @return int
     */
    public function getGroupingThreshold()
    {
        return 1;
    }

}
