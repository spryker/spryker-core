<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Controller;

use Generated\Shared\Transfer\RestAccessTokensAttributesTransfer;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class AccessTokensResourceController extends AbstractRestController
{
    /**
     * @param \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createAccessTokensReader()
            ->processAccessTokenRequest($restAccessTokensAttributesTransfer);
    }
}
