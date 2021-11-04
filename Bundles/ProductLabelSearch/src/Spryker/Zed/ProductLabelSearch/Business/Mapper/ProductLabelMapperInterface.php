<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\Mapper;

interface ProductLabelMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelTransfer> $productLabelTransfers
     *
     * @return array<array<int>>
     */
    public function getProductLabelIdsMappedByIdProductAbstractAndStoreName(array $productLabelTransfers): array;
}
