<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

interface JenkinsConfigurationReaderInterface
{
    /**
     * @param string $schedulerId
     *
     * @return string[]
     */
    public function getJenkinsAuthCredentials(string $schedulerId): array;

    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return string
     */
    public function getJenkinsBaseUrlBySchedulerId(string $schedulerId, string $urlPath): string;
}
