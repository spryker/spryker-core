<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AuthRestApi\AuthRestApiFactory getFactory()
 */
class AuthRestApiClient extends AbstractClient implements AuthRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        return $this->getFactory()->createAuthRestApiZedStub()->processAccessToken($oauthRequestTransfer);
    }
}
