<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReader;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReaderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\Relationship\CompanyUserByShareDetailResourceRelationshipExpander;
use Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\Relationship\CompanyUserResourceRelationshipExpanderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Customer\CustomerExpander;
use Spryker\Glue\CompanyUsersRestApi\Processor\Customer\CustomerExpanderInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapper;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilder;
use Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface getClient()
 */
class CompanyUsersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserClient(),
            $this->getClient(),
            $this->createCompanyUserRestResponseBuilder(),
            $this->getCompanyUserStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface
     */
    public function createCompanyUserRestResponseBuilder(): CompanyUserRestResponseBuilderInterface
    {
        return new CompanyUserRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCompanyUserMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface
     */
    public function createCompanyUserMapper(): CompanyUserMapperInterface
    {
        return new CompanyUserMapper();
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Customer\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander();
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\Relationship\CompanyUserResourceRelationshipExpanderInterface
     */
    public function createCompanyUserByShareDetailResourceRelationshipExpander(): CompanyUserResourceRelationshipExpanderInterface
    {
        return new CompanyUserByShareDetailResourceRelationshipExpander(
            $this->createCompanyUserRestResponseBuilder(),
            $this->createCompanyUserMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUsersRestApiToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface
     */
    public function getCompanyUserStorageClient(): CompanyUsersRestApiToCompanyUserStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::CLIENT_COMPANY_USER_STORAGE);
    }
}
