<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business\Reader;

use Generated\Shared\Transfer\SalesStatisticTransfer;

interface ReaderInterface
{
    /**
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderCountStatisticByDays(int $days): SalesStatisticTransfer;

    /**
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer;

    /**
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getTopOrderStatistic(int $countProduct): SalesStatisticTransfer;
}
