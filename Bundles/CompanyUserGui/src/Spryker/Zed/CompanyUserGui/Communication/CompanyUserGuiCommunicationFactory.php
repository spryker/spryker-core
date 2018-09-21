<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication;

use Spryker\Zed\CompanyUserGui\CompanyUserGuiDependencyProvider;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserGuiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserGuiDependencyProvider::FACADE_COMPANY_USER);
    }
}
