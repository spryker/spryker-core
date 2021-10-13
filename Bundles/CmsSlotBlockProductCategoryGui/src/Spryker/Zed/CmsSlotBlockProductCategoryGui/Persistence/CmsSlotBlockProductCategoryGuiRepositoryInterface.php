<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence;

interface CmsSlotBlockProductCategoryGuiRepositoryInterface
{
    /**
     * @param array<int>|null $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getProductAbstracts(?array $productAbstractIds = []): array;
}
