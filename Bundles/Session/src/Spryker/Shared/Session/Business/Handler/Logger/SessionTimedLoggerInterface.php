<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Logger;

interface SessionTimedLoggerInterface
{

    /**
     * @return void
     */
    public function startTiming();

    /**
     * @param string $key
     *
     * @return void
     */
    public function logTimedMetric($key);

}
