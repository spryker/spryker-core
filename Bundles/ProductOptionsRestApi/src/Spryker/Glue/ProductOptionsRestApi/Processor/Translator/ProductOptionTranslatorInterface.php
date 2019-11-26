<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Translator;

use Generated\Shared\Transfer\ItemTransfer;

interface ProductOptionTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function translateProductAbstractOptionStorageTransfers(
        array $productAbstractOptionStorageTransfers,
        string $localeName
    ): array;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function translateItemTransfer(ItemTransfer $itemTransfer, string $localeName): ItemTransfer;
}
