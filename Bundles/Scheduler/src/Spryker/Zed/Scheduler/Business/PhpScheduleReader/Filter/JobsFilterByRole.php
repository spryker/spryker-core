<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

use Generated\Shared\Transfer\SchedulerFilterTransfer;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Exception\RoleNotAllowedException;
use Spryker\Zed\Scheduler\SchedulerConfig;

class JobsFilterByRole extends AbstractJobsFilter implements ChainableJobsFilterInterface
{
    /**
     * @see \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper::KEY_ROLE
     */
    protected const KEY_ROLE = 'role';

    /**
     * @var string[]
     */
    protected $roles;

    /**
     * @var string
     */
    protected $defaultRole;

    /**
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $config
     */
    public function __construct(SchedulerConfig $config)
    {
        $this->roles = $config->getRoles();
        $this->defaultRole = $config->getDefaultRole();
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerFilterTransfer $filterTransfer
     * @param array $jobs
     *
     * @return array
     */
    public function filterJobs(SchedulerFilterTransfer $filterTransfer, array $jobs): array
    {
        $roles = $filterTransfer->getRoles();

        if (count($roles) === 0) {
            return $this->next($filterTransfer, $jobs);
        }

        $this->assertRoles($roles);

        $filteredJobs = [];

        foreach ($jobs as $job) {
            if (in_array($job['role'] ?? $this->defaultRole, $roles, true)) {
                $filteredJobs[] = $job;
            }
        }

        return $this->next($filterTransfer, $filteredJobs);
    }

    /**
     * @param array $roles
     *
     * @throws \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Exception\RoleNotAllowedException
     *
     * @return void
     */
    protected function assertRoles(array $roles): void
    {
        foreach ($roles as $role) {
            if (!in_array($role, $this->roles, true)) {
                throw new RoleNotAllowedException(sprintf(
                    '%s is not in the list of allowed job roles.',
                    $role
                ));
            }
        }
    }
}
