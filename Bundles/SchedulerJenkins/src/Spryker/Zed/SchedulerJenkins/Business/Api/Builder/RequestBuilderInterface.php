<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Builder;

use Psr\Http\Message\RequestInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;

interface RequestBuilderInterface
{
    /**
     * @param string $requestMethod
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $urlPath
     * @param string $body
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function buildPsrRequest(string $requestMethod, ConfigurationProviderInterface $configurationProvider, string $urlPath, string $body = ''): RequestInterface;
}
