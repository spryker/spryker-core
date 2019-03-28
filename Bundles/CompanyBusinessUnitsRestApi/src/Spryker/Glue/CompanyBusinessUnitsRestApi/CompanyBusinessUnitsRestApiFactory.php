<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapper;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReader;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReaderInterface;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitRestResponseBuilder;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyBusinessUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship\CompanyBusinessUnitResourceRelationshipExpanderInterface
     */
    public function createCompanyBusinessUnitResourceRelationshipExpander(): CompanyBusinessUnitResourceRelationshipExpanderInterface
    {
        return new CompanyBusinessUnitResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createCompanyBusinessUnitMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitReader
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->createCompanyBusinessUnitMapper(),
            $this->getCompanyBusinessUnitClient(),
            $this->createCompanyBusinessUnitRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitMapperInterface
     */
    public function createCompanyBusinessUnitMapper(): CompanyBusinessUnitMapperInterface
    {
        return new CompanyBusinessUnitMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\CompanyBusinessUnitRestResponseBuilderInterface
     */
    public function createCompanyBusinessUnitRestResponseBuilder(): CompanyBusinessUnitRestResponseBuilderInterface
    {
        return new CompanyBusinessUnitRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Dependency\Client\CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitsRestApiToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitsRestApiDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }
}
