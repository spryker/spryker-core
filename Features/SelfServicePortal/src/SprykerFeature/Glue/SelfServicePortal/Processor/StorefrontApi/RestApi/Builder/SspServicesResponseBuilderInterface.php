<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface SspServicesResponseBuilderInterface
{
    public function createSspServiceCollectionRestResponse(
        SspServiceCollectionTransfer $sspServiceCollectionTransfer
    ): RestResponseInterface;
}
