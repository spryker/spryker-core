<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Configuration;

interface ConfigurationProviderInterface
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
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\WrongJenkinsConfiguration
     *
     * @return string
     */
    public function getJenkinsBaseUrlBySchedulerId(string $schedulerId, string $urlPath): string;

    /**
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool;
}
