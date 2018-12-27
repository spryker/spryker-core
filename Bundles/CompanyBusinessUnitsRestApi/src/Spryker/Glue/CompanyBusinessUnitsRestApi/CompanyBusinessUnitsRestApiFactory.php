<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper\CompanyBusinessUnitMapper;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyBusinessUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Mapper\CompanyBusinessUnitMapperInterface
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapperInterface
    {
        return new CompanyBusinessUnitMapper($this->getCompanyBusinessUnitMapperPlugins());
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApiExtension\Dependency\Plugin\CompanyBusinessUnitMapperPluginInterface[]
     */
    public function getCompanyBusinessUnitMapperPlugins(): array
    {
        return $this->getProvidedDependency(CompanyBusinessUnitsRestApiDependencyProvider::PLUGINS_COMPANY_BUSINESS_UNIT_MAPPER);
    }
}
