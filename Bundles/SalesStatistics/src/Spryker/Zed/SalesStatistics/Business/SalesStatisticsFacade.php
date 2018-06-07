<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Generated\Shared\Transfer\SalesStatisticTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsBusinessFactory getFactory()
 */
class SalesStatisticsFacade extends AbstractFacade implements SalesStatisticsFacadeInterface
{
    /**
     * @api
     *
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderStatisticByCountDay($days): SalesStatisticTransfer
    {
        return $this->getFactory()->createReader()->getOrderCountStatisticByDays($days);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer
    {
        return $this->getFactory()->createReader()->getStatusOrderStatistic();
    }

    /**
     * @api
     *
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getTopOrderStatistic(int $countProduct): SalesStatisticTransfer
    {
        return $this->getFactory()->createReader()->getTopOrderStatistic($countProduct);
    }
}
