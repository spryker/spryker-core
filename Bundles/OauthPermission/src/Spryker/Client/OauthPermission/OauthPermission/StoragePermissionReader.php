<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission\OauthPermission;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Generated\Shared\Transfer\OauthPermissionStorageKeyTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Client\OauthPermission\OauthPermissionConfig;
use Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class StoragePermissionReader implements PermissionReaderInterface
{
    /**
     * @param \Spryker\Shared\OauthPermission\KeyBuilder\OauthPermissionKeyBuilderInterface $permissionKeyBuilder
     * @param \Spryker\Client\OauthPermission\Dependency\Client\OauthPermissionToStorageRedisClientInterface $storageRedisClient
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface $oauthService
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        protected OauthPermissionKeyBuilderInterface $permissionKeyBuilder,
        protected OauthPermissionToStorageRedisClientInterface $storageRedisClient,
        protected OauthPermissionToOauthServiceInterface $oauthService,
        protected OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissions(): PermissionCollectionTransfer
    {
        $request = Request::createFromGlobals();
        $authorizationHeader = $request->headers->get(OauthPermissionConfig::HEADER_AUTHORIZATION);

        if (!$authorizationHeader) {
            return new PermissionCollectionTransfer();
        }

        $accessToken = $this->extractToken($authorizationHeader);

        if (!$accessToken) {
            return new PermissionCollectionTransfer();
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($accessToken);
        $customerIdentifierTransfer = $this->extractCustomerIdentifier($oauthAccessTokenDataTransfer);
        if ($customerIdentifierTransfer === null) {
            return new PermissionCollectionTransfer();
        }

        $key = $this->generateKey($customerIdentifierTransfer);

        if ($key === null) {
            return new PermissionCollectionTransfer();
        }

        $storedPermissions = $this->storageRedisClient->get($key);

        return (new PermissionCollectionTransfer())->fromArray(
            $storedPermissions ?? [],
            true,
        );
    }

    /**
     * @param string $authorizationHeader
     *
     * @return string|null
     */
    protected function extractToken(string $authorizationHeader): ?string
    {
        if (preg_match('/^Bearer\s+(.+)$/i', $authorizationHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer|null
     */
    protected function extractCustomerIdentifier(OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer): ?CustomerIdentifierTransfer
    {
        $oauthUserIdDecoded = $this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);
        if (!$oauthUserIdDecoded) {
            return null;
        }

        return (new CustomerIdentifierTransfer())
            ->fromArray($oauthUserIdDecoded, true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     *
     * @return string|null
     */
    protected function generateKey(CustomerIdentifierTransfer $customerIdentifierTransfer): ?string
    {
        if (!$customerIdentifierTransfer->getIdCompanyUser()) {
            return null;
        }

        $oauthPermissionStorageKeyTransfer = (new OauthPermissionStorageKeyTransfer())
            ->setIdCompanyUser($customerIdentifierTransfer->getIdCompanyUser());

        return $this->permissionKeyBuilder->generateKey($oauthPermissionStorageKeyTransfer);
    }
}
