<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspServicesResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspServicesMapperInterface;

class SspServicesReader implements SspServicesReaderInterface
{
    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SspServicesResponseBuilderInterface $restSspServicesResponseBuilder,
        protected SspServicesMapperInterface $sspServicesMapper
    ) {
    }

    public function getSspServices(RestRequestInterface $restRequest): RestResponseInterface
    {
        $sspServiceCriteriaTransfer = $this->sspServicesMapper->mapRestRequestToSspServiceCriteriaTransfer($restRequest);

        $sspServiceCollectionTransfer = $this->selfServicePortalClient->getSspServiceCollection($sspServiceCriteriaTransfer);

        return $this->restSspServicesResponseBuilder->createSspServiceCollectionRestResponse($sspServiceCollectionTransfer);
    }
}
