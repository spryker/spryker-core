<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Business;

use Spryker\Zed\CompanyMailConnector\Business\Company\CompanyStatusMailer;
use Spryker\Zed\CompanyMailConnector\Business\Company\CompanyStatusMailerInterface;
use Spryker\Zed\CompanyMailConnector\CompanyMailConnectorDependencyProvider;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CompanyMailConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyMailConnector\Business\Company\CompanyStatusMailerInterface
     */
    public function createCompanyStatusMailer(): CompanyStatusMailerInterface
    {
        return new CompanyStatusMailer(
            $this->getMailFacade(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface
     */
    protected function getMailFacade(): CompanyMailConnectorToMailFacadeInterface
    {
        return $this->getProvidedDependency(CompanyMailConnectorDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToCompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade(): CompanyMailConnectorToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyMailConnectorDependencyProvider::FACADE_COMPANY_USER);
    }
}
