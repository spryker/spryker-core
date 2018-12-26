<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReader;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReaderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserResourceMapper;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyUsersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserClient(),
            $this->getResourceBuilder(),
            $this->createCompanyUserResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserResourceMapperInterface
     */
    public function createCompanyUserResourceMapper(): CompanyUserResourceMapperInterface
    {
        return new CompanyUserResourceMapper($this->getCompanyUsersResourceMapperPlugin());
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUsersRestApiToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceMapperPluginInterface[]
     */
    protected function getCompanyUsersResourceMapperPlugin(): array
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::PLUGINS_COMPANY_USERS_RESOURCE_MAPPER);
    }
}
