<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission\OauthPermission;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Client\OauthPermission\OauthPermissionConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use {@link \Spryker\Client\OauthPermission\OauthPermission\StoragePermissionReader} instead.
 */
class OauthPermissionReader implements OauthPermissionReaderInterface
{
    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface $oauthService
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        OauthPermissionToOauthServiceInterface $oauthService,
        OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionsFromOauthToken(): PermissionCollectionTransfer
    {
        $request = Request::createFromGlobals();
        $authorizationToken = $request->headers->get(OauthPermissionConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return new PermissionCollectionTransfer();
        }

        $accessToken = $this->extractToken($authorizationToken);

        if (!$accessToken) {
            return new PermissionCollectionTransfer();
        }

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($accessToken);

        $customerPermissions = $this->extractPermissionsFromOauthToken($oauthAccessTokenDataTransfer);

        if (!$customerPermissions) {
            return new PermissionCollectionTransfer();
        }

        return $customerPermissions;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer|null
     */
    protected function extractPermissionsFromOauthToken(OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer): ?PermissionCollectionTransfer
    {
        $oauthUserIdDecoded = $this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true);
        if (!$oauthUserIdDecoded) {
            return null;
        }

        $customerIdentifier = (new CustomerIdentifierTransfer())
            ->fromArray($oauthUserIdDecoded, true);

        return $customerIdentifier->getPermissions();
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractToken(string $authorizationToken): ?string
    {
        $explodedToken = explode(' ', $authorizationToken);

        if (count($explodedToken) < 2) {
            return null;
        }

        return end($explodedToken);
    }
}
