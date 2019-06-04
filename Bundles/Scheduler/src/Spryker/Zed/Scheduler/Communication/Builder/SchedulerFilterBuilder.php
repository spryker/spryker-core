<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Builder;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

class SchedulerFilterBuilder implements SchedulerFilterBuilderInterface
{
    /**
     * @var string[]
     */
    protected $roles = [];

    /**
     * @var string[]
     */
    protected $schedulerIds = [];

    /**
     * @var string[]
     */
    protected $jobNames = [];

    /**
     * @var string
     */
    protected $store = '';

    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    public function build(): SchedulerFilterTransfer
    {
        $filterTransfer = new SchedulerFilterTransfer();

        $filterTransfer
            ->setRoles($this->roles)
            ->setSchedulers($this->schedulerIds)
            ->setJobs($this->jobNames)
            ->setStore($this->store);

        return $filterTransfer;
    }

    /**
     * @param string[] $roles
     *
     * @return static
     */
    public function withRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string[] $schedulerIds
     *
     * @return static
     */
    public function withSchedulerIds(array $schedulerIds)
    {
        $this->schedulerIds = $schedulerIds;

        return $this;
    }

    /**
     * @param string[] $jobNames
     *
     * @return static
     */
    public function withJobNames(array $jobNames)
    {
        $this->jobNames = $jobNames;

        return $this;
    }

    /**
     * @param string $store
     *
     * @return static
     */
    public function withStore(string $store)
    {
        $this->store = $store;

        return $this;
    }
}
