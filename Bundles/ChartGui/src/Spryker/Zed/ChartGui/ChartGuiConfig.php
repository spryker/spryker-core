<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartGui;

use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Chart\ChartConfig getSharedConfig()
 */
class ChartGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getDefaultChartLayout(): ChartLayoutTransfer
    {
        return new ChartLayoutTransfer();
    }
}
