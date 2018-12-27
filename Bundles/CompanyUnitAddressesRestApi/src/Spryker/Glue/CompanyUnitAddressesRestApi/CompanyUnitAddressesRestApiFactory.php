<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi;

use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressAttributesMapper;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressAttributesMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyUnitAddressesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\Mapper\CompanyBusinessUnitAddressAttributesMapperInterface
     */
    public function createCompanyBusinessUnitAddressAttributesMapper(): CompanyBusinessUnitAddressAttributesMapperInterface
    {
        return new CompanyBusinessUnitAddressAttributesMapper();
    }
}
