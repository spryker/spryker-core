<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Logger;

use Spryker\Shared\NewRelicApi\NewRelicApiInterface;

class NewRelicSessionTimedLogger implements SessionTimedLoggerInterface
{

    /**
     * @var \Spryker\Shared\NewRelicApi\NewRelicApiInterface
     */
    protected $newRelicApi;

    /**
     * @var int
     */
    protected $startTimeMicroseconds;

    /**
     * @param \Spryker\Shared\NewRelicApi\NewRelicApiInterface $newRelicApi
     */
    public function __construct(NewRelicApiInterface $newRelicApi)
    {
        $this->newRelicApi = $newRelicApi;
    }

    /**
     * @return void
     */
    public function startTiming()
    {
        $this->startTimeMicroseconds = microtime(true);
    }

    /**
     * @param string $metricName
     *
     * @return void
     */
    public function logTimedMetric($metricName)
    {
        $duration = microtime(true) - $this->startTimeMicroseconds;

        $this->newRelicApi->addCustomMetric($metricName, $duration);
    }

}
