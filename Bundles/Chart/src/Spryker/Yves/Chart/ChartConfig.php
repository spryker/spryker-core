<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart;

use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Chart\ChartConfig getSharedConfig()
 */
class ChartConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getChartTypes(): array
    {
        return $this->getSharedConfig()->getChartTypes();
    }

    /**
     * @return string
     */
    public function getDefaultChartType(): string
    {
        return $this->getSharedConfig()->getDefaultChartType();
    }

    /**
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getDefaultChartLayout(): ChartLayoutTransfer
    {
        return $this->getSharedConfig()->getDefaultChartLayout();
    }
}
