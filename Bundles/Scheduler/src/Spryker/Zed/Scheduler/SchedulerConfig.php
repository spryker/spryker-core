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
    public const ROLE_ADMIN = 'admin';
    public const ROLE_REPORTING = 'reporting';
    public const ROLE_EMPTY = 'empty';

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
    public function getRoles(): array
    {
        return [
            static::ROLE_ADMIN,
            static::ROLE_REPORTING,
            static::ROLE_EMPTY,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultRole(): string
    {
        return static::ROLE_ADMIN;
    }

    /**
     * @return string[]
     */
    public function getEnabledSchedulers(): array
    {
        return $this->get(SchedulerConstants::ENABLED_SCHEDULERS, []);
    }
}
