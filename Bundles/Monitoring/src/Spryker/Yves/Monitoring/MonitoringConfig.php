<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring;

use Spryker\Shared\Monitoring\MonitoringConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class MonitoringConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getIgnorableTransactionRouteNames(): array
    {
        return $this->get(MonitoringConstants::IGNORABLE_TRANSACTIONS, []);
    }
}
