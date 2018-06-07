<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business\Model;

use Generated\Shared\Transfer\SalesStatisticTransfer;
use Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface;

class Reader
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
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderCountStatisticByDays($days): SalesStatisticTransfer
    {
        return $this->repository->getOrderCountStatisticByDays($days);
    }

    /**
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer
    {
        return $this->repository->getStatusOrderStatistic();
    }
}
