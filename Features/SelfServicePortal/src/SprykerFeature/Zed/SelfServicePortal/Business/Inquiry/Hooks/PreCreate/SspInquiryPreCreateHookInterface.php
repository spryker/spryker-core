<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;

interface SspInquiryPreCreateHookInterface
{
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool;
}
