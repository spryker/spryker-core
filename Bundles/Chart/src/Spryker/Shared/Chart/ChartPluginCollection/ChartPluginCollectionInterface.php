<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\ChartPluginCollection;

interface ChartPluginCollectionInterface
{
    /**
     * @param string $name
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    public function getChartPluginByName($name);
}
