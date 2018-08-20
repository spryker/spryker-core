<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business\Reader;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface $repository
     */
    public function __construct(SalesStatisticsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $days
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getOrderCountStatisticByDays(int $days): ChartDataTraceTransfer
    {
        return $this->repository->getOrderCountStatisticByDays($days);
    }

    /**
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getStatusOrderStatistic(): ChartDataTraceTransfer
    {
        return $this->repository->getStatusOrderStatistic();
    }

    /**
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer
    {
        return $this->repository->getTopOrderStatistic($countProduct);
    }
}
