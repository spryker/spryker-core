<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator;

use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface SspAssetsCreatorInterface
{
    public function create(
        RestRequestInterface $restRequest,
        RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer
    ): RestResponseInterface;
}
