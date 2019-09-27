<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth;

use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Oauth\OauthServiceFactory getFactory()
 */
class OauthService extends AbstractService implements OauthServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer
     */
    public function extractAccessTokenData(string $accessToken): OauthAccessTokenDataTransfer
    {
        return $this->getFactory()->createTokenDataExtractor()->extractAccessTokenData($accessToken);
    }
}
