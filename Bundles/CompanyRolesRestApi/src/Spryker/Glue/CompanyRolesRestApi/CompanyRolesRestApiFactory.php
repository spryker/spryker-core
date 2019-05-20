<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapper;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Reader\CompanyRoleReader;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Reader\CompanyRoleReaderInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Relationship\CompanyRoleResourceRelationshipExpander;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Relationship\CompanyRoleResourceRelationshipExpanderInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder\CompanyRoleRestResponseBuilder;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder\CompanyRoleRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyRolesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Relationship\CompanyRoleResourceRelationshipExpanderInterface
     */
    public function createCompanyRoleResourceRelationshipExpander(): CompanyRoleResourceRelationshipExpanderInterface
    {
        return new CompanyRoleResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createCompanyRoleMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Reader\CompanyRoleReaderInterface
     */
    public function createCompanyRoleReader(): CompanyRoleReaderInterface
    {
        return new CompanyRoleReader(
            $this->getCompanyRoleClient(),
            $this->createCompanyRoleMapper(),
            $this->createCompanyRoleRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface
     */
    public function createCompanyRoleMapper(): CompanyRoleMapperInterface
    {
        return new CompanyRoleMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder\CompanyRoleRestResponseBuilderInterface
     */
    public function createCompanyRoleRestResponseBuilder(): CompanyRoleRestResponseBuilderInterface
    {
        return new CompanyRoleRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface
     */
    public function getCompanyRoleClient(): CompanyRolesRestApiToCompanyRoleClientInterface
    {
        return $this->getProvidedDependency(CompanyRolesRestApiDependencyProvider::CLIENT_COMPANY_ROLE);
    }
}
