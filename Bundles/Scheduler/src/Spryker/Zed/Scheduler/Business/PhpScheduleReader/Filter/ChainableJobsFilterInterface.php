<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter;

interface ChainableJobsFilterInterface extends JobsFilterInterface
{
    /**
     * @param \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Filter\JobsFilterInterface $nextFilter
     *
     * @return static
     */
    public function setNextFilter(JobsFilterInterface $nextFilter);
}
