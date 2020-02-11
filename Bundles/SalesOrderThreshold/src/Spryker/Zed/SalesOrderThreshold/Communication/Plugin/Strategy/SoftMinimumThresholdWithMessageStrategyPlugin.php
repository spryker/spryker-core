<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy;

use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;

class SoftMinimumThresholdWithMessageStrategyPlugin extends AbstractSalesOrderThresholdStrategyPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_SOFT;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getGroup(): string
    {
        return SalesOrderThresholdConfig::GROUP_SOFT;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    public function isValid(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): bool
    {
        return !($salesOrderThresholdValueTransfer->getValue() < 1 || $salesOrderThresholdValueTransfer->getFee());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    public function isApplicable(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): bool
    {
        return $salesOrderThresholdValueTransfer->getValue() < $salesOrderThresholdValueTransfer->getThreshold();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return int|null
     */
    public function calculateFee(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): ?int
    {
        return null;
    }
}
