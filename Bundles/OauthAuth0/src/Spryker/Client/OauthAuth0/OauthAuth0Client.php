<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthAuth0;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OauthAuth0\OauthAuth0Factory getFactory()
 */
class OauthAuth0Client extends AbstractClient implements OauthAuth0ClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        return $this->getFactory()->createAuth0()->getAccessToken($accessTokenRequestTransfer);
    }
}
