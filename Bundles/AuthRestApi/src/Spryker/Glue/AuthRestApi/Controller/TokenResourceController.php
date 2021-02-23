<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Controller;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Glue\Kernel\Controller\FormattedAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class TokenResourceController extends FormattedAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postAction(Request $httpRequest): JsonResponse
    {
        $oauthRequestTransfer = (new OauthRequestTransfer())->fromArray($httpRequest->request->all(), true);

        return $this->getFactory()->createOauthToken()->createAccessToken($oauthRequestTransfer);
    }
}
