<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler;

use Spryker\Shared\Scheduler\SchedulerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SchedulerConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the path to PHP file to retrieve schedule for particular scheduler
     *
     * @api
     *
     * @param string $idScheduler
     *
     * @return string
     */
    public function getPhpSchedulerReaderPath(string $idScheduler): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            $idScheduler . '.php',
        ]);
    }

    /**
     * @return string[]
     */
    public function getEnabledSchedulers(): array
    {
        return (array)$this->get(SchedulerConstants::ENABLED_SCHEDULERS, []);
    }
}
