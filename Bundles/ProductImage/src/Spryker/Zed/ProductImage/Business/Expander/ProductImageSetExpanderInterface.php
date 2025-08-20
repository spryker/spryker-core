<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Expander;

interface ProductImageSetExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function expandProductImageSetCollectionWithProductImageAlternativeTextTranslations(array $productImageSetTransfers): array;
}
