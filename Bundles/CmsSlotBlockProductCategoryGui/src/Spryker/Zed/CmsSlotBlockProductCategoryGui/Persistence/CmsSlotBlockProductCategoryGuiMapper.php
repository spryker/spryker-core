<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Propel\Runtime\Collection\Collection;

class CmsSlotBlockProductCategoryGuiMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $productAbstractEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function mapProductAbstractCollectionToTransfers(Collection $productAbstractEntityCollection): array
    {
        $productAbstracts = [];

        foreach ($productAbstractEntityCollection as $productAbstract) {
            $productAbstracts[] = (new ProductAbstractTransfer())
                ->setIdProductAbstract($productAbstract->getIdProductAbstract())
                ->setName($productAbstract->getName())
                ->setSku($productAbstract->getSku());
        }

        return $productAbstracts;
    }
}
