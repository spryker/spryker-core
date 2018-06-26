<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Controller;

use Generated\Shared\Transfer\RestRefreshTokensAttributesTransfer;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class RefreshTokensResourceController extends AbstractRestController
{
    /**
     * @param \Generated\Shared\Transfer\RestRefreshTokensAttributesTransfer $restResfresTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRefreshTokensAttributesTransfer $restResfresTokensAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()
            ->createRefreshTokenReader()
            ->processAccessTokenRequest($restResfresTokensAttributesTransfer);
    }
}
