<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SchedulerConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the path to the cronjobs definition, their config and schedule.
     *
     * @api
     *
     * @return string
     */
    public function getCronJobsDefinitionPhpFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            'jobs.php',
        ]);
    }
}
