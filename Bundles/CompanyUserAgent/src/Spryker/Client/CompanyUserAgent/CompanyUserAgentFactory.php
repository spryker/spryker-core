<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserAgent;

use Spryker\Client\CompanyUserAgent\Dependency\Client\CompanyUserAgentToZedRequestClientInterface;
use Spryker\Client\CompanyUserAgent\Zed\CompanyUserAgentStub;
use Spryker\Client\CompanyUserAgent\Zed\CompanyUserAgentStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CompanyUserAgent\CompanyUserAgentConfig getConfig()
 */
class CompanyUserAgentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUserAgent\Zed\CompanyUserAgentStubInterface
     */
    public function createCompanyUserAgentStub(): CompanyUserAgentStubInterface
    {
        return new CompanyUserAgentStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CompanyUserAgent\Dependency\Client\CompanyUserAgentToZedRequestClientInterface
     */
    public function getZedRequestClient(): CompanyUserAgentToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAgentDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
