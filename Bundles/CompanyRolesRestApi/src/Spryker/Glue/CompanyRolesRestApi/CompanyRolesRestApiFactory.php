<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleMapper;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleMapperInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleReader;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleReaderInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleRestResponseBuilder;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleRestResponseBuilderInterface;
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
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleReader
     */
    public function createCompanyRoleReader(): CompanyRoleReaderInterface
    {
        return new CompanyRoleReader(
            $this->createCompanyRoleMapper(),
            $this->getCompanyRoleClient(),
            $this->createCompanyRoleRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleMapperInterface
     */
    public function createCompanyRoleMapper(): CompanyRoleMapperInterface
    {
        return new CompanyRoleMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\CompanyRoleRestResponseBuilderInterface
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
