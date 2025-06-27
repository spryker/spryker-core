<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;

interface SspInquiryValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateSspInquiry(SspInquiryTransfer $sspInquiryTransfer): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function validateRequestGrantedToCreateInquiry(
        SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): SspInquiryCollectionResponseTransfer;
}
