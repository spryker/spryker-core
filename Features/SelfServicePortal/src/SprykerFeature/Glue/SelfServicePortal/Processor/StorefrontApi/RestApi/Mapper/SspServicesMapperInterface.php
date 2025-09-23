<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface SspServicesMapperInterface
{
    public function mapRestRequestToSspServiceCriteriaTransfer(
        RestRequestInterface $restRequest
    ): SspServiceCriteriaTransfer;
}
