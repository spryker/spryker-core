<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\JenkinsResponseTransfer;

interface JenkinsApiInterface
{
    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return \Generated\Shared\Transfer\JenkinsResponseTransfer
     */
    public function executeGetRequest(string $schedulerId, string $urlPath): JenkinsResponseTransfer;

    /**
     * @param string $schedulerId
     * @param string $urlPath
     * @param string $body
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return \Generated\Shared\Transfer\JenkinsResponseTransfer
     */
    public function executePostRequest(string $schedulerId, string $urlPath, string $body = ''): JenkinsResponseTransfer;
}
