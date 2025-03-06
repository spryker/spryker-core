<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Symfony\Component\HttpFoundation\Request;

interface SspInquiryReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(Request $request, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer|null
     */
    public function getSspInquiry(string $reference, CompanyUserTransfer $companyUserTransfer): ?SspInquiryTransfer;
}
