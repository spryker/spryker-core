<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Generated\Shared\Transfer\SalesStatisticTransfer;

interface SalesStatisticsFacadeInterface
{
    /**
     * @api
     *
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderStatisticByCountDay($days): SalesStatisticTransfer;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer;
}
