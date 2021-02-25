<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;

interface OauthTokenInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAccessToken(OauthRequestTransfer $oauthRequestTransfer): JsonResponse;
}
