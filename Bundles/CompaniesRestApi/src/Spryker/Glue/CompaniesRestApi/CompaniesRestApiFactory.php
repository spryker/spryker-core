<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi;

use Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyRestResponseBuilder;
use Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyRestResponseBuilderInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapper;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReader;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReaderInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship\CompanyResourceRelationshipExpander;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship\CompanyResourceRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompaniesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReader
     */
    public function createCompanyReader(): CompanyReaderInterface
    {
        return new CompanyReader(
            $this->getCompanyClient(),
            $this->createCompanyMapper(),
            $this->createCompanyRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface
     */
    public function createCompanyMapper(): CompanyMapperInterface
    {
        return new CompanyMapper();
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship\CompanyResourceRelationshipExpanderInterface
     */
    public function createCompanyResourceRelationshipExpander(): CompanyResourceRelationshipExpanderInterface
    {
        return new CompanyResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\CompanyRestResponseBuilderInterface
     */
    public function createCompanyRestResponseBuilder(): CompanyRestResponseBuilderInterface
    {
        return new CompanyRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface
     */
    public function getCompanyClient(): CompaniesRestApiToCompanyClientInterface
    {
        return $this->getProvidedDependency(CompaniesRestApiDependencyProvider::CLIENT_COMPANY);
    }
}
