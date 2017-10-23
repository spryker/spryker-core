<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class HeartbeatConfig extends AbstractBundleConfig
{
    /**
     * @return \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface[]
     */
    public function getHealthIndicator()
    {
        return [];
    }
}
