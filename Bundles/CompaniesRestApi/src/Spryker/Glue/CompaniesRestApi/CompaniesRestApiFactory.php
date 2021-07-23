<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi;

use Spryker\Glue\CompaniesRestApi\Dependency\Client\CompaniesRestApiToCompanyClientInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyByCompanyBusinessUnitResourceRelationshipExpander;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyByCompanyRoleResourceRelationshipExpander;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyByCompanyUserResourceRelationshipExpander;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyByQuoteRequestResourceRelationshipExpander;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyResourceRelationshipExpanderInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapper;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReader;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReaderInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilder;
use Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig getConfig()
 */
class CompaniesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyResourceRelationshipExpanderInterface
     */
    public function createCompanyByCompanyUserResourceRelationshipExpander(): CompanyResourceRelationshipExpanderInterface
    {
        return new CompanyByCompanyUserResourceRelationshipExpander(
            $this->createCompanyRestResponseBuilder(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyResourceRelationshipExpanderInterface
     */
    public function createCompanyByCompanyRoleResourceRelationshipExpander(): CompanyResourceRelationshipExpanderInterface
    {
        return new CompanyByCompanyRoleResourceRelationshipExpander(
            $this->createCompanyRestResponseBuilder(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyResourceRelationshipExpanderInterface
     */
    public function createCompanyByCompanyBusinessUnitResourceRelationshipExpander(): CompanyResourceRelationshipExpanderInterface
    {
        return new CompanyByCompanyBusinessUnitResourceRelationshipExpander(
            $this->createCompanyRestResponseBuilder(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Expander\CompanyResourceRelationshipExpanderInterface
     */
    public function createCompanyByQuoteRequestResourceRelationshipExpander(): CompanyResourceRelationshipExpanderInterface
    {
        return new CompanyByQuoteRequestResourceRelationshipExpander(
            $this->createCompanyRestResponseBuilder(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\Reader\CompanyReaderInterface
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
     * @return \Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface
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
