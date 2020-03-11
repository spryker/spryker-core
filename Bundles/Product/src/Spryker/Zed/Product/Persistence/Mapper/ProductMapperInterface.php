<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

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
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer
     */
    public function mapProductAbstractLocalizedAttributesEntityToProductAbstractLocalizedAttributesTransfer(
        SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity,
        ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
    ): ProductAbstractLocalizedAttributesTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[] $productAbstractLocalizedAttributesEntities
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[] $localizedAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[]
     */
    public function mapProductAbstractLocalizedAttributesEntitiesToProductAbstractLocalizedAttributeTransfers(
        ObjectCollection $productAbstractLocalizedAttributesEntities,
        array $localizedAttributesTransfers
    ): array;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function mapProductAbstractLocalizedAttributesEntityToLocalizedAttributesTransfer(
        SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer;
}
