<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\SspInquiryTransfer;

interface SspInquiryValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateSspInquiry(SspInquiryTransfer $sspInquiryTransfer): ArrayObject;
}
