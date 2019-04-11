<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

interface ProductCollectionFormatterInterface
{
    /**
     * @param array $productAbstractArray
     *
     * @return array
     */
    public function formatArray(array $productAbstractArray): array;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $productAbstractTransfers
     *
     * @return array
     */
    public function formatTransfers(array $productAbstractTransfers): array;
}
