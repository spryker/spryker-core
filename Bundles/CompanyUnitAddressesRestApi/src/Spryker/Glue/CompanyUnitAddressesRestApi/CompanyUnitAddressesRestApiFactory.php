<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi;

use Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client\CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressMapper;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressMapperInterface;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressReader;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressReaderInterface;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressRestResponseBuilder;
use Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyUnitAddressesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressReader
     */
    public function createCompanyUnitAddressReader(): CompanyUnitAddressReaderInterface
    {
        return new CompanyUnitAddressReader(
            $this->createCompanyUnitAddressMapper(),
            $this->getCompanyUnitAddressClient(),
            $this->createCompanyUnitAddressRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressMapperInterface
     */
    public function createCompanyUnitAddressMapper(): CompanyUnitAddressMapperInterface
    {
        return new CompanyUnitAddressMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress\CompanyUnitAddressRestResponseBuilderInterface
     */
    public function createCompanyUnitAddressRestResponseBuilder(): CompanyUnitAddressRestResponseBuilderInterface
    {
        return new CompanyUnitAddressRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CompanyUnitAddressesRestApi\Dependency\Client\CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface
     */
    public function getCompanyUnitAddressClient(): CompanyUnitAddressesRestApiToCompanyUnitAddressClientInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressesRestApiDependencyProvider::CLIENT_COMPANY_UNIT_ADDRESS);
    }
}
