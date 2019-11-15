<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ItemTransfer;

interface StorageReaderInterface
{
    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributesTransfersByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName
    ): array;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return string[]
     */
    public function getTranslationsForItemProductOptions(ItemTransfer $itemTransfer, string $localeName): array;
}
