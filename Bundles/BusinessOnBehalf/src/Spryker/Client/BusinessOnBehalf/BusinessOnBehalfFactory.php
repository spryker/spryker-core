<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf;

use Spryker\Client\BusinessOnBehalf\Checker\CompanyUserChecker;
use Spryker\Client\BusinessOnBehalf\Checker\CompanyUserCheckerInterface;
use Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface;
use Spryker\Client\BusinessOnBehalf\Zed\BusinessOnBehalfStub;
use Spryker\Client\BusinessOnBehalf\Zed\BusinessOnBehalfStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class BusinessOnBehalfFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\BusinessOnBehalf\Checker\CompanyUserCheckerInterface
     */
    public function createCustomerChecker(): CompanyUserCheckerInterface
    {
        return new CompanyUserChecker(
            $this->getCompanyUserChangeAllowedCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Client\BusinessOnBehalf\Zed\BusinessOnBehalfStubInterface
     */
    public function createZedBusinessOnBehalfStub(): BusinessOnBehalfStubInterface
    {
        return new BusinessOnBehalfStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface
     */
    public function getZedRequestClient(): BusinessOnBehalfToZedRequestClientInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CompanyUserChangeAllowedCheckPluginInterface[]
     */
    public function getCompanyUserChangeAllowedCheckPlugins(): array
    {
        return $this->getProvidedDependency(BusinessOnBehalfDependencyProvider::PLUGINS_COMPANY_USER_CHANGE_ALLOWED_CHECK);
    }
}
