<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyMailConnector\Business\CompanyMailConnectorBusinessFactory;
use Spryker\Zed\CompanyMailConnector\CompanyMailConnectorDependencyProvider;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeBridge;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveCompany(array $seedData = []): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder($seedData))->build();
        $companyTransfer->setIdCompany(null);

        $this->prepareCompanyMailConnectorDependency();

        return $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveActiveCompany(array $seedData = []): CompanyTransfer
    {
        $seedData[CompanyTransfer::IS_ACTIVE] = true;

        return $this->haveCompany($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveInactiveCompany(array $seedData = []): CompanyTransfer
    {
        $seedData[CompanyTransfer::IS_ACTIVE] = false;

        return $this->haveCompany($seedData);
    }

    /**
     * @return void
     */
    protected function prepareCompanyMailConnectorDependency(): void
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
