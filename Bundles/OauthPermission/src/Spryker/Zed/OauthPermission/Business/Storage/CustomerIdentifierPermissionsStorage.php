<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Business\Storage;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface;
use Spryker\Zed\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface;
use Spryker\Zed\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Zed\OauthPermission\OauthPermissionConfig;

class CustomerIdentifierPermissionsStorage implements CustomerIdentifierPermissionsStorageInterface
{
    /**
     * @param \Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface $keyBuilder
     * @param \Spryker\Zed\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface $storageRedisClient
     * @param \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeInterface $permissionFacade
     * @param \Spryker\Zed\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OauthPermission\OauthPermissionConfig $permissionConfig
     */
    public function __construct(
        protected OauthPermissionKeyBuilderInterface $keyBuilder,
        protected OauthPermissionToStorageRedisClientInterface $storageRedisClient,
        protected OauthPermissionToCompanyUserFacadeInterface $companyUserFacade,
        protected OauthPermissionToPermissionFacadeInterface $permissionFacade,
        protected OauthPermissionToUtilEncodingServiceInterface $utilEncodingService,
        protected OauthPermissionConfig $permissionConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function storePermissions(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        $permissionsCollectionTransfer = $this->findPermissions($customerIdentifierTransfer);

        if ($permissionsCollectionTransfer === null) {
            return $customerIdentifierTransfer;
        }

        $key = $this->generateKey($customerIdentifierTransfer);
        $this->storageRedisClient->set(
            $key,
            $this->utilEncodingService->encodeJson($permissionsCollectionTransfer->toArray()),
            $this->permissionConfig->getStoredPermissionTTL(),
        );

        return $customerIdentifierTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return string
     */
    protected function generateKey(CustomerIdentifierTransfer $customerIdentifierTransfer): string
    {
        $oauthPermissionStorageKeyTransfer = (new OauthPermissionStorageKeyTransfer())
            ->setIdCompanyUser($customerIdentifierTransfer->getIdCompanyUser());

        return $this->keyBuilder->generateKey($oauthPermissionStorageKeyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer|null
     */
    protected function findPermissions(CustomerIdentifierTransfer $customerIdentifierTransfer): ?PermissionCollectionTransfer
    {
        if (!$customerIdentifierTransfer->getIdCompanyUser()) {
            return null;
        }

        $companyUserTransfer = $this->companyUserFacade->findActiveCompanyUserByUuid(
            (new CompanyUserTransfer())->setUuid($customerIdentifierTransfer->getIdCompanyUser()),
        );

        if (!$companyUserTransfer) {
            return null;
        }

        return $this
            ->permissionFacade
            ->getPermissionsByIdentifier((string)$companyUserTransfer->getIdCompanyUser());
    }
}
