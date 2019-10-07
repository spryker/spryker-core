<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface getRepository()
 */
class SalesStatisticsFacade extends AbstractFacade implements SalesStatisticsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getOrderStatisticByCountDay($days): ChartDataTraceTransfer
    {
        return $this->getFactory()->createReader()->getOrderCountStatisticByDays($days);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getStatusOrderStatistic(): ChartDataTraceTransfer
    {
        return $this->getFactory()->createReader()->getStatusOrderStatistic();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer
    {
        return $this->getFactory()->createReader()->getTopOrderStatistic($countProduct);
    }
}
