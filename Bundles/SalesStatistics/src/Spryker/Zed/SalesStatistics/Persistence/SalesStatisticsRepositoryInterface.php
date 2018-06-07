<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence;

use Generated\Shared\Transfer\SalesStatisticTransfer;

interface SalesStatisticsRepositoryInterface
{
    /**
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderCountStatisticByDays($days): SalesStatisticTransfer;

    /**
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer;
}
