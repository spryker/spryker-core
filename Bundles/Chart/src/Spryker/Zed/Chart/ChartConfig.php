<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Chart\ChartConfig getSharedConfig()
 */
class ChartConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getChartTypes()
    {
        return $this->getSharedConfig()->getChartTypes();
    }

    /**
     * @return string
     */
    public function getDefaultChartType()
    {
        return $this->getSharedConfig()->getDefaultChartType();
    }
}
