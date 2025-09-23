<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business;

use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilder;
use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthPermission\Business\Expander\CompanyUserIdentifierExpander;
use Spryker\Zed\OauthPermission\Business\Expander\CompanyUserIdentifierExpanderInterface;
use Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpanderInterface;
use Spryker\Zed\OauthPermission\Business\Filter\OauthUserIdentifierFilter;
use Spryker\Zed\OauthPermission\Business\Filter\OauthUserIdentifierFilterInterface;
use Spryker\Zed\OauthPermission\Business\Storage\CompanyUserPermissionsStorage;
use Spryker\Zed\OauthPermission\Business\Storage\CompanyUserPermissionsStorageInterface;
use Spryker\Zed\OauthPermission\Business\Storage\CustomerIdentifierPermissionsStorage;
use Spryker\Zed\OauthPermission\Business\Storage\CustomerIdentifierPermissionsStorageInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;
use Spryker\Zed\OauthPermission\OauthPermissionDependencyProvider;

/**
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 */
class OauthPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @deprecated Stays for Backward Compatibility reasons. Will be removed in the next major release.
     *
     * @return \Spryker\Zed\OauthPermission\Business\Expander\CustomerIdentifierExpanderInterface
     */
    public function createCustomerIdentifierExpander(): CustomerIdentifierExpanderInterface
    {
        return new CustomerIdentifierExpander(
            $this->getPermissionFacade(),
            $this->getCompanyUserFacade(),
        );
    }

    /**
     * @deprecated Stays for Backward Compatibility reasons. Will be removed in the next major release.
     *
     * @return \Spryker\Zed\OauthPermission\Business\Expander\CompanyUserIdentifierExpanderInterface
     */
    public function createCompanyUserIdentifierExpander(): CompanyUserIdentifierExpanderInterface
    {
        return new CompanyUserIdentifierExpander($this->getPermissionFacade());
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Business\Filter\OauthUserIdentifierFilterInterface
     */
    public function createOauthUserIdentifierFilter(): OauthUserIdentifierFilterInterface
    {
        return new OauthUserIdentifierFilter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Business\Storage\CustomerIdentifierPermissionsStorageInterface
     */
    public function createCustomerIdentifierPermissionStorage(): CustomerIdentifierPermissionsStorageInterface
    {
        return new CustomerIdentifierPermissionsStorage(
            $this->createPermissionStorageKeyBuilder(),
            $this->getStorageRedisClient(),
            $this->getCompanyUserFacade(),
            $this->getPermissionFacade(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Business\Storage\CompanyUserPermissionsStorageInterface
     */
    public function createCompanyUserPermissionStorage(): CompanyUserPermissionsStorageInterface
    {
        return new CompanyUserPermissionsStorage(
            $this->createPermissionStorageKeyBuilder(),
            $this->getStorageRedisClient(),
            $this->getPermissionFacade(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface
     */
    public function createPermissionStorageKeyBuilder(): OauthPermissionKeyBuilderInterface
    {
        return new OauthPermissionKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface
     */
    public function getPermissionFacade(): OauthPermissionToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): OauthPermissionToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface
     */
    public function getStorageRedisClient()
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::CLIENT_STORAGE_REDIS);
    }

    /**
     * @return \Spryker\Zed\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(OauthPermissionDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
