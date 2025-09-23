<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface SspInquiriesResponseBuilderInterface
{
    public function createSspInquiryRestResponseFromSspInquiryCollectionTransfer(
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer,
        string $localeName
    ): RestResponseInterface;

    public function createSspInquiryCollectionRestResponse(
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
    ): RestResponseInterface;

    public function createInquiryRestResponseFromSspInquiryCollectionResponseTransfer(
        SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer,
        string $localeName
    ): RestResponseInterface;
}
