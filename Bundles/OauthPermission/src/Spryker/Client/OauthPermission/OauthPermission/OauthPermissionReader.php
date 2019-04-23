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
use Spryker\Glue\Kernel\Application;

class OauthPermissionReader implements OauthPermissionReaderInterface
{
    /**
     * @var \Spryker\Glue\Kernel\Application
     */
    protected $glueApplication;

    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\Kernel\Application $glueApplication
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToOauthServiceInterface $oauthService
     * @param \Spryker\Client\OauthPermission\Dependency\Service\OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        Application $glueApplication,
        OauthPermissionToOauthServiceInterface $oauthService,
        OauthPermissionToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->glueApplication = $glueApplication;
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getOauthCustomerPermissions(): PermissionCollectionTransfer
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->glueApplication->get('request');
        $authorizationToken = $request->headers->get(OauthPermissionConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return new PermissionCollectionTransfer();
        }

        [$type, $accessToken] = $this->extractToken($authorizationToken);

        $oauthAccessTokenDataTransfer = $this->oauthService->extractAccessTokenData($accessToken);

        $customerIdentifier = (new CustomerIdentifierTransfer())
            ->fromArray($this->utilEncodingService->decodeJson($oauthAccessTokenDataTransfer->getOauthUserId(), true));
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
