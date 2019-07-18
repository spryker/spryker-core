<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthPermission;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OauthPermission\OauthPermissionFactory getFactory()
 */
class OauthPermissionClient extends AbstractClient implements OauthPermissionClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollectionFromAuthorizationHeader(): PermissionCollectionTransfer
    {
        return $this->getFactory()
            ->createOauthPermissionReader()
            ->getPermissionsFromOauthToken();
    }
}
