<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\Collection;

interface ProductMapperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferForSuggestion(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductEntityToProductConcreteTransferWithoutStores(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToProductConcreteTransferWithoutRelations(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function mapProductConcreteTransferToProductEntity(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyProduct $productEntity
    ): SpyProduct;

    /**
     * @param \Propel\Runtime\Collection\Collection $productEntityCollection
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductEntityCollectionPrimaryKeysToProductConcreteCollectionTransfer(
        Collection $productEntityCollection,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer;
}
