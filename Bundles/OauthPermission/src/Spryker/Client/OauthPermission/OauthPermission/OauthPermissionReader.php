<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission\OauthPermission;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface;
use Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface;
use Spryker\Client\OauthPermission\OauthPermissionConfig;
use Symfony\Component\HttpFoundation\Request;

class OauthPermissionReader implements OauthPermissionReaderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    protected $utilEncodeService;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface $oauthService
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface $utilEncodeService
     */
    public function __construct(
        Request $request,
        OauthPermissionToOauthServiceInterface $oauthService,
        OauthPermissionToUtilEncodingServiceInterface $utilEncodeService
    ) {
        $this->request = $request;
        $this->oauthService = $oauthService;
        $this->utilEncodeService = $utilEncodeService;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getOauthCustomerPermissions(): PermissionCollectionTransfer
    {
        $authorizationToken = $this->request->headers->get(OauthPermissionConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return new PermissionCollectionTransfer();
        }

        [$type, $accessToken] = $this->extractToken($authorizationToken);

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($accessToken);

        $customerIdentifier = (new CustomerIdentifierTransfer())
            ->fromArray($this->utilEncodeService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true));
        $customerPermissions = $customerIdentifier->getPermissions();

        if (!$customerPermissions) {
            return new PermissionCollectionTransfer();
        }

        return $customerPermissions;
    }

    /**
     * @param string $authorizationToken
     *
     * @return array
     */
    protected function extractToken(string $authorizationToken): array
    {
        return preg_split('/\s+/', $authorizationToken);
    }
}
