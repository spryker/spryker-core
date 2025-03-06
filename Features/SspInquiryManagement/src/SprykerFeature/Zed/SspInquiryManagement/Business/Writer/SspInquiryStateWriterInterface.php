<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Writer;

use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;

interface SspInquiryStateWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiry(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer;
}
