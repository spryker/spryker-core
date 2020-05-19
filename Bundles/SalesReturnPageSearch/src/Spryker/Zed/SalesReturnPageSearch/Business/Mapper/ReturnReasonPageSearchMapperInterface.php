<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;

interface ReturnReasonPageSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string[][] $returnReasonTranslations
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer
     */
    public function mapReturnReasonTransferToReturnReasonPageSearchTransfer(
        ReturnReasonTransfer $returnReasonTransfer,
        ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer,
        LocaleTransfer $localeTransfer,
        array $returnReasonTranslations
    ): ReturnReasonPageSearchTransfer;
}
