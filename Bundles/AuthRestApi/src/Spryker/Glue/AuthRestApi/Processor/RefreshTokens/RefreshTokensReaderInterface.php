<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\RefreshTokens;

use Generated\Shared\Transfer\RestRefreshTokensAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface RefreshTokensReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestRefreshTokensAttributesTransfer $restRefreshTokenAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processAccessTokenRequest(RestRefreshTokensAttributesTransfer $restRefreshTokenAttributesTransfer): RestResponseInterface;
}
