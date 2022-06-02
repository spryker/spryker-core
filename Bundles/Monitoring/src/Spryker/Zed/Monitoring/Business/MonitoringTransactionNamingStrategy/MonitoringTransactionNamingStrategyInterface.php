<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy;

use Generated\Shared\Transfer\MonitoringTransactionEventTransfer;

interface MonitoringTransactionNamingStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer
     *
     * @return bool
     */
    public function isApplicable(MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer
     *
     * @return string|null
     */
    public function getMonitoringTransactionName(MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer): ?string;
}
