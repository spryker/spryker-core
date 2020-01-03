<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

class CmsBlockProductConnectorMapper implements CmsBlockProductConnectorMapperInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap
     */
    public const PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME = 'name';
    public const PRODUCT_ABSTRACT_COLUMN_SKU = 'Sku';
    public const PRODUCT_ABSTRACT_COLUMN_ID = 'IdProductAbstract';

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransfer(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productAbstractTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productAbstractTransfer->setName($productAbstractEntity->getVirtualColumn(static::PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME));
        $productAbstractTransfer->setSku($productAbstractEntity->getSku());

        return $productAbstractTransfer;
    }
}
