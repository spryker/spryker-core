<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Psr\Http\Message\ResponseInterface;

interface JenkinsApiInterface
{
    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Exception\JenkinsBaseUrlNotFound
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function executeGetRequest(string $schedulerId, string $urlPath): ResponseInterface;

    /**
     * @param string $schedulerId
     * @param string $urlPath
     * @param string $xmlTemplate
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Exception\JenkinsBaseUrlNotFound
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function executePostRequest(string $schedulerId, string $urlPath, string $xmlTemplate = ''): ResponseInterface;
}
