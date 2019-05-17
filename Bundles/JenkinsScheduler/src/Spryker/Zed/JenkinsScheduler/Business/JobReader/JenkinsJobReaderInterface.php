<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business\JobReader;

interface JenkinsJobReaderInterface
{
    /**
     * @param string $schedulerId
     *
     * @return array
     */
    public function getExistingJobs(string $schedulerId): array;
}
