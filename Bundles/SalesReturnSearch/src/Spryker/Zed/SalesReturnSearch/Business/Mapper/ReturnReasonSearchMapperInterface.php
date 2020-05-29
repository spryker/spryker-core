<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;

interface ReturnReasonSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer $returnReasonSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string[][] $returnReasonTranslations
     *
     * @return \Generated\Shared\Transfer\ReturnReasonSearchTransfer
     */
    public function mapReturnReasonTransferToReturnReasonSearchTransfer(
        ReturnReasonTransfer $returnReasonTransfer,
        ReturnReasonSearchTransfer $returnReasonSearchTransfer,
        LocaleTransfer $localeTransfer,
        array $returnReasonTranslations
    ): ReturnReasonSearchTransfer;
}
