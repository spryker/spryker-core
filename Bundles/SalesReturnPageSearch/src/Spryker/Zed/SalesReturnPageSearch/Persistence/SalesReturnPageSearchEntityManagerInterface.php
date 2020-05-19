<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Persistence;

use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;

interface SalesReturnPageSearchEntityManagerInterface
{
    /**
     * @param int[] $returnReasonIds
     *
     * @return void
     */
    public function deleteReturnReasonSearchByReturnReasonIds(array $returnReasonIds): void;

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer
     *
     * @return void
     */
    public function saveReturnReasonSearchPageSearch(ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer): void;
}
