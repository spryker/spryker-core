<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartOrder\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\ChartOrder\ChartOrderDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ChartOrder\ChartOrderConfig getConfig()
 */
class ChartOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSpySalesOrderQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(ChartOrderDependencyProvider::SALES_ORDER_QUERY);
    }
}
