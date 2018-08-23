<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\RestAccessTokensAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface AccessTokensReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processAccessTokenRequest(RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer): RestResponseInterface;
}
