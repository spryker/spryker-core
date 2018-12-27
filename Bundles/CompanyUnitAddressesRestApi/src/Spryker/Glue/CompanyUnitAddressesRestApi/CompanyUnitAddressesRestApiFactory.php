<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi;

use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressMapper;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyUnitAddressesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressMapperInterface
     */
    public function createCompanyBusinessUnitAddressMapper(): CompanyBusinessUnitAddressMapperInterface
    {
        return new CompanyBusinessUnitAddressMapper();
    }
}
