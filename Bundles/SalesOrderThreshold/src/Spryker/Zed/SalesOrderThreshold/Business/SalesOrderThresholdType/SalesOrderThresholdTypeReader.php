<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdType;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface;

class SalesOrderThresholdTypeReader implements SalesOrderThresholdTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    protected $salesOrderThresholdStrategyResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface
     */
    protected $salesOrderThresholdRepository;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository
     */
    public function __construct(
        SalesOrderThresholdStrategyResolverInterface $salesOrderThresholdStrategyResolver,
        SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository
    ) {
        $this->salesOrderThresholdStrategyResolver = $salesOrderThresholdStrategyResolver;
        $this->salesOrderThresholdRepository = $salesOrderThresholdRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function getSalesOrderThresholdTypeByKey(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer {
        $this->salesOrderThresholdStrategyResolver
            ->resolveSalesOrderThresholdStrategy($salesOrderThresholdTypeTransfer->getKey());

        return $this->salesOrderThresholdRepository->getSalesOrderThresholdTypeByKey($salesOrderThresholdTypeTransfer);
    }
}
