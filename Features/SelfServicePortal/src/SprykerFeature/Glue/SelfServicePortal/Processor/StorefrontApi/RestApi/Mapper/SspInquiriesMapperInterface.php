<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\RestSspInquiriesAttributesTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface SspInquiriesMapperInterface
{
    public function mapRestRequestToSspInquiryCriteriaTransfer(RestRequestInterface $restRequest): SspInquiryCriteriaTransfer;

    public function mapRestSspInquiriesAttributesToSspInquiryCollectionRequestTransfer(
        RestSspInquiriesAttributesTransfer $restSspInquiriesAttributesTransfer,
        RestRequestInterface $restRequest
    ): SspInquiryCollectionRequestTransfer;

    public function mapSspInquiryTransferToRestSspInquiriesAttributesTransfer(
        SspInquiryTransfer $sspInquiryTransfer
    ): RestSspInquiriesAttributesTransfer;
}
