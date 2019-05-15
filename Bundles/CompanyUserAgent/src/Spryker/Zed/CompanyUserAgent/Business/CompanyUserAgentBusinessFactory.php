<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserAgent\Business;

use Spryker\Zed\CompanyUserAgent\Business\Reader\CompanyUserReader;
use Spryker\Zed\CompanyUserAgent\Business\Reader\CompanyUserReaderInterface;
use Spryker\Zed\CompanyUserAgent\CompanyUserAgentDependencyProvider;
use Spryker\Zed\CompanyUserAgent\Dependency\Facade\CompanyUserAgentToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUserAgent\CompanyUserAgentConfig getConfig()
 */
class CompanyUserAgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserAgent\Business\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserAgent\Dependency\Facade\CompanyUserAgentToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserAgentToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserAgentDependencyProvider::FACADE_COMPANY_USER);
    }
}
