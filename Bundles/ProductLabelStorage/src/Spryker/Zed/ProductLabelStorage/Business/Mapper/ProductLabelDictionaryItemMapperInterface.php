<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductLabelCollectionTransfer;

interface ProductLabelDictionaryItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelCollectionTransfer $productLabelCollectionTransfer
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    public function mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        ProductLabelCollectionTransfer $productLabelCollectionTransfer
    ): array;
}
