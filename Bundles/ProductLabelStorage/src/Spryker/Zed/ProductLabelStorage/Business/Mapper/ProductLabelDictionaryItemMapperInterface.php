<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Mapper;

interface ProductLabelDictionaryItemMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelTransfer> $productLabelTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    public function mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        array $productLabelTransfers
    ): array;
}
