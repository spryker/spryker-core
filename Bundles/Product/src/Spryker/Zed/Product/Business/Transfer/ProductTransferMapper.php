<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Transfer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;

class ProductTransferMapper implements ProductTransferMapperInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /**
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     */
    public function __construct(AttributeEncoderInterface $attributeEncoder)
    {
        $this->attributeEncoder = $attributeEncoder;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function convertProductAbstract(SpyProductAbstract $productAbstractEntity)
    {
        $productAbstractTransfer = new ProductAbstractTransfer();

        $productData = $productAbstractEntity->toArray();
        if (isset($productData[ProductAbstractTransfer::ATTRIBUTES])) {
            $attributes = $this->attributeEncoder->decodeAttributes($productAbstractEntity->getAttributes());
            $productAbstractTransfer->setAttributes($attributes);

            unset($productData[ProductAbstractTransfer::ATTRIBUTES]);
        }

        $productAbstractTransfer->fromArray($productData, true);

        return $productAbstractTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function convertProductAbstractCollection(ObjectCollection $productAbstractEntityCollection)
    {
        $transferList = [];
        foreach ($productAbstractEntityCollection as $productAbstractEntity) {
            $transferList[] = $this->convertProductAbstract($productAbstractEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function convertProduct(SpyProduct $productEntity)
    {
        $productTransfer = $this->mapProductConcreteTransfer($productEntity);

        $attributes = $this->attributeEncoder->decodeAttributes($productEntity->getAttributes());
        $productTransfer->setAttributes($attributes);
        $productTransfer->setIdProductConcrete($productEntity->getIdProduct());

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract|null $productAbstractEntity */
        $productAbstractEntity = $productEntity->getSpyProductAbstract();
        if ($productAbstractEntity) {
            $productTransfer->setAbstractSku($productAbstractEntity->getSku());
            $productTransfer->setFkProductAbstract($productAbstractEntity->getIdProductAbstract());
        }

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer $productEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapSpyProductEntityTransferToProductConcreteTransfer(SpyProductEntityTransfer $productEntityTransfer): ProductConcreteTransfer
    {
        $productTransfer = $this->mapProductConcreteFromData($productEntityTransfer->toArray());
        $attributes = $this->attributeEncoder->decodeAttributes($productEntityTransfer->getAttributes());
        $productTransfer->setAttributes($attributes)
                        ->setIdProductConcrete($productEntityTransfer->getIdProduct());

        $productAbstractEntityTransfer = $productEntityTransfer->getSpyProductAbstract();
        if ($productAbstractEntityTransfer !== null) {
            $productTransfer->setAbstractSku($productAbstractEntityTransfer->getSku());
        }

        return $productTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProduct> $productCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function convertProductCollection(Collection $productCollection)
    {
        $transferList = [];
        foreach ($productCollection as $productEntity) {
            $transferList[] = $this->convertProduct($productEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapProductConcreteTransfer(SpyProduct $productEntity)
    {
        $productData = $productEntity->toArray();

        return $this->mapProductConcreteFromData($productData);
    }

    /**
     * @param array<string, mixed> $productData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapProductConcreteFromData(array $productData): ProductConcreteTransfer
    {
        if (isset($productData[ProductConcreteTransfer::ATTRIBUTES])) {
            unset($productData[ProductConcreteTransfer::ATTRIBUTES]);
        }

        $productTransfer = (new ProductConcreteTransfer())
            ->fromArray($productData, true);

        return $productTransfer;
    }
}
