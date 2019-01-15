<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReader;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReaderInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapper;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyBusinessUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReader
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->createCompanyBusinessUnitMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapperInterface
    {
        return new CompanyBusinessUnitMapper();
    }
}
