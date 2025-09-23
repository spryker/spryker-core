<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspInquiriesResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspInquiriesMapperInterface;

class SspInquiriesReader implements SspInquiriesReaderInterface
{
    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SspInquiriesResponseBuilderInterface $restSspInquiriesResponseBuilder,
        protected SspInquiriesMapperInterface $sspInquiriesMapper
    ) {
    }

    public function getSspInquiry(RestRequestInterface $restRequest): RestResponseInterface
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $sspInquiryCriteriaTransfer = $this->sspInquiriesMapper->mapRestRequestToSspInquiryCriteriaTransfer($restRequest);
        $sspInquiryCollectionTransfer = $this->selfServicePortalClient->getSspInquiryCollection($sspInquiryCriteriaTransfer);

        return $this->restSspInquiriesResponseBuilder->createSspInquiryRestResponseFromSspInquiryCollectionTransfer($sspInquiryCollectionTransfer, $localeName);
    }

    public function getSspInquiries(RestRequestInterface $restRequest): RestResponseInterface
    {
        $sspInquiryCriteriaTransfer = $this->sspInquiriesMapper->mapRestRequestToSspInquiryCriteriaTransfer($restRequest);

        $sspInquiryCollectionTransfer = $this->selfServicePortalClient->getSspInquiryCollection($sspInquiryCriteriaTransfer);

        return $this->restSspInquiriesResponseBuilder->createSspInquiryCollectionRestResponse($sspInquiryCollectionTransfer);
    }
}
