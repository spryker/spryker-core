<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;

interface JenkinsApiInterface
{
    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function executeGetRequest(string $schedulerId, string $urlPath): SchedulerJenkinsResponseTransfer;

    /**
     * @param string $schedulerId
     * @param string $urlPath
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function executePostRequest(string $schedulerId, string $urlPath, string $body = ''): SchedulerJenkinsResponseTransfer;
}
