<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
