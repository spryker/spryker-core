<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;

interface SalesOrderThresholdStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns strategy key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Specification:
     * - Returns strategy group name.
     *
     * @api
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Specification:
     * - Checks is threshold transfer valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    public function isValid(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): bool;

    /**
     * Specification:
     * - Checks is threshold transfer applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    public function isApplicable(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): bool;

    /**
     * Specification:
     * - Calculates fee for threshold.
     * - Fee is expected as an integer in cents.
     * - If no fee is applicable it should return null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return int|null
     */
    public function calculateFee(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): ?int;

    /**
     * Specification:
     * - Creates SalesOrderThresholdTypeTransfer from strategy.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function toTransfer(): SalesOrderThresholdTypeTransfer;
}
