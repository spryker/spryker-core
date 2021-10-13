<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Extractor;

use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return array<string>
     */
    public function extractUniqueOrderReferencesFromReturn(ReturnTransfer $returnTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return array<string>
     */
    public function extractUniqueItemStateLabelsFromReturn(ReturnTransfer $returnTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return array<int>
     */
    public function extractSalesOrderItemIdsFromReturn(ReturnTransfer $returnTransfer): array;
}
