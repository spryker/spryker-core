<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;

interface SspInquiryCriteriaExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCriteriaTransfer
     */
    public function expandCriteriaBasedOnCompanyUserPermissions(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCriteriaTransfer;
}
