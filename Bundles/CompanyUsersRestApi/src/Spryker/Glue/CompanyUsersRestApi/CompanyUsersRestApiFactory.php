<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUsers\CompanyUsersReader;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUsers\CompanyUsersReaderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapper;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyUsersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUsers\CompanyUsersReaderInterface
     */
    public function createCompanyUsersReader(): CompanyUsersReaderInterface
    {
        return new CompanyUsersReader(
            $this->getCompanyUserClient(),
            $this->getResourceBuilder(),
            $this->createCompanyUsersMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapperInterface
     */
    public function createCompanyUsersMapper(): CompanyUsersResourceMapperInterface
    {
        return new CompanyUsersResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUsersRestApiToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::CLIENT_COMPANY_USER);
    }
}
