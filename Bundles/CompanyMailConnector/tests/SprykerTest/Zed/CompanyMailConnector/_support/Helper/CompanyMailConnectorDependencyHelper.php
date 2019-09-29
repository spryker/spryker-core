<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyMailConnector\Helper;

use Codeception\Module;
use Spryker\Zed\CompanyMailConnector\Business\CompanyMailConnectorBusinessFactory;
use Spryker\Zed\CompanyMailConnector\CompanyMailConnectorDependencyProvider;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeBridge;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyMailConnectorDependencyHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function haveCompanyMailConnectorToMailDependency(): void
    {
        $mailFacade = $this->getLocator()->mail()->facade();
        $companyMailConnectorToMailFacadeBridge = new CompanyMailConnectorToMailFacadeBridge($mailFacade);

        $this->getDependencyHelper()->setDependency(
            CompanyMailConnectorDependencyProvider::FACADE_MAIL,
            $companyMailConnectorToMailFacadeBridge,
            CompanyMailConnectorBusinessFactory::class
        );
    }
}
