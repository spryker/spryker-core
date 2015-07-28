<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ItemGrouperConfig extends AbstractBundleConfig
{
    /**
     * @return int
     */
    public function getGroupingThreshold()
    {
        return -1;
    }
}
