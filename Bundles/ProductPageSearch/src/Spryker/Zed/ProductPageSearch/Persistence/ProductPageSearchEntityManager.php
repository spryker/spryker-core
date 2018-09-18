<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchEntityManager extends AbstractEntityManager implements ProductPageSearchEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function saveProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): ProductConcretePageSearchTransfer
    {
        $productConcreteSearchPageEntity = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByIdProductConcretePageSearch($productConcretePageSearchTransfer->getIdProductConcretePageSearch())
            ->findOneOrCreate();

        $productConcreteSearchPageEntity = $this->getFactory()
            ->createProductPageSearchMapper()
            ->mapProductConcretePageSearchTransferToEntity($productConcretePageSearchTransfer, $productConcreteSearchPageEntity);

        $productConcreteSearchPageEntity->save();

        return $this->getFactory()
            ->createProductPageSearchMapper()
            ->mapProductConcretePageSearchEntityToTransfer($productConcreteSearchPageEntity, $productConcretePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return bool
     */
    public function deleteProductConcretePageSearch(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): bool
    {
        $productConcreteSearchPageEntity = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByIdProductConcretePageSearch($productConcretePageSearchTransfer->getIdProductConcretePageSearch())
            ->findOne();

        if ($productConcreteSearchPageEntity === null) {
            return false;
        }

        $productConcreteSearchPageEntity->delete();

        return true;
    }
}
