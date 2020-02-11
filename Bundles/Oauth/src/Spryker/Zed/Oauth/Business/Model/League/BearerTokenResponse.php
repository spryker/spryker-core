<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse as LeagueBearerTokenResponse;

class BearerTokenResponse extends LeagueBearerTokenResponse
{
    /**
     * {@inheritDoc}
     *
     * @param \League\OAuth2\Server\Entities\AccessTokenEntityInterface $accessToken
     *
     * @return array
     */
    protected function getExtraParams(AccessTokenEntityInterface $accessToken): array
    {
        return json_decode($accessToken->getUserIdentifier(), true);
    }
}
