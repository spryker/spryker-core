<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Configuration;

interface ConfigurationProviderInterface
{
    /**
     * @return string[]
     */
    public function getJenkinsAuthCredentials(): array;

    /**
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\InvalidJenkinsConfiguration
     *
     * @return string
     */
    public function buildJenkinsApiUrl(string $urlPath): string;

    /**
     * @return bool
     */
    public function isJenkinsCsrfProtectionEnabled(): bool;
}
