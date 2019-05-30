<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Builder;

use Generated\Shared\Transfer\SchedulerFilterTransfer;

interface SchedulerFilterBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\SchedulerFilterTransfer
     */
    public function build(): SchedulerFilterTransfer;

    /**
     * @param array $roles
     *
     * @return static
     */
    public function withRoles(array $roles);

    /**
     * @param array $schedulerIds
     *
     * @return static
     */
    public function withSchedulerIds(array $schedulerIds);

    /**
     * @param array $jobNames
     *
     * @return static
     */
    public function withJobNames(array $jobNames);

    /**
     * @param string $store
     *
     * @return static
     */
    public function withStore(string $store);
}
