<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReader;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReaderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapper;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
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
            $this->createCompanyUserMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface
     */
    public function createCompanyUserMapper(): CompanyUserMapperInterface
    {
        return new CompanyUserMapper($this->getCompanyUserAttributesMapperPlugin());
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUsersRestApiToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserAttributesMapperPluginInterface[]
     */
    protected function getCompanyUserAttributesMapperPlugin(): array
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::PLUGINS_COMPANY_USER_ATTRIBUTES_MAPPER);
    }
}
