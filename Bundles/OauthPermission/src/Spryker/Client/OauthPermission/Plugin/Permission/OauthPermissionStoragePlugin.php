<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission\Plugin\Permission;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthPermission\OauthPermissionConfig;
use Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Client\OauthPermission\OauthPermissionFactory getFactory()
 */
class OauthPermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollection(): PermissionCollectionTransfer
    {
        $request = $this->getFactory()->getGlueApplication()->get('request');
        $authorizationToken = $request->headers->get(OauthPermissionConfig::HEADER_AUTHORIZATION);

        if (!$authorizationToken) {
            return new PermissionCollectionTransfer();
        }

        [$type, $accessToken] = $this->extractToken($authorizationToken);

        $oauthAccessTokenDataTransfer = $this->getFactory()
            ->getOauthService()
            ->extractAccessTokenData($accessToken);

        $converter = $this->getFactory()->createOauthPermissionConverter();

        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($oauthAccessTokenDataTransfer->getOauthScopes() as $oauthScope) {
            $permissionCollectionTransfer->addPermission($converter->convertScopeToPermission($oauthScope));
        }

        return $permissionCollectionTransfer;
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
