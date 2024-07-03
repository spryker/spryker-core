<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader;

interface ProductReaderInterface
{
    /**
     * @param list<string> $productConcreteSkus
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductTransfersIndexedBySku(array $productConcreteSkus): array;
}
