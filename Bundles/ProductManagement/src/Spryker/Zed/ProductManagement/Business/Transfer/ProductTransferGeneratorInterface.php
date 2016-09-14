<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Transfer;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductTransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function convertProductAbstract(SpyProductAbstract $productAbstractEntity);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[]|\Propel\Runtime\Collection\ObjectCollection $productAbstractEntityCollection
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer[]
     */
    public function convertProductAbstractCollection(ObjectCollection $productAbstractEntityCollection);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    public function convertProduct(SpyProduct $productEntity);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct[]|\Propel\Runtime\Collection\ObjectCollection $productCollection
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function convertProductCollection(ObjectCollection $productCollection);

}
