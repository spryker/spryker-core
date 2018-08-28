<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring;

use Spryker\Shared\Monitoring\MonitoringConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MonitoringConfig extends AbstractBundleConfig
{
    /**
     * @return mixed
     */
    public function getIgnorableTransactions()
    {
        return $this->get(MonitoringConstants::IGNORABLE_TRANSACTIONS, []);
    }
}
