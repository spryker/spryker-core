<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AttributeLoader implements AttributeLoaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface
     */
    protected $attributeMerger;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface $attributeMerger
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, AttributeMergerInterface $attributeMerger, AttributeEncoderInterface $attributeEncoder)
    {
        $this->productQueryContainer = $productQueryContainer;
        $this->attributeMerger = $attributeMerger;
        $this->attributeEncoder = $attributeEncoder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeys(ProductAbstractTransfer $productAbstractTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $productAbstractEntity = $this->getProductAbstractEntity($productAbstractTransfer);

        return $this->getCombinedAbstractAttributeKeysForEntity($productAbstractEntity, $localeTransfer);
    }

    /**
     * @param int[] $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer|null|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeysForProductIds($productIds, ?LocaleTransfer $localeTransfer = null)
    {
        $productAbstractEntities = $this->getProductAbstractEntitiesFromIds($productIds);
        $productIdsWithAttributes = [];
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productIdsWithAttributes[$productAbstractEntity->getIdProductAbstract()] = $this->getCombinedAbstractAttributeKeysForEntity($productAbstractEntity, $localeTransfer);
        }

        return $productIdsWithAttributes;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer Deprecated: default null will be removed in the next major
     *
     * @return array
     */
    protected function getCombinedAbstractAttributeKeysForEntity(SpyProductAbstract $productAbstractEntity, ?LocaleTransfer $localeTransfer = null)
    {
        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer->setAbstractAttributes($this->getAbstractAttributes($productAbstractEntity));

        if ($localeTransfer) {
            $rawProductAttributesTransfer->setConcreteLocalizedAttributes($this->getAbstractLocalizedAttributes($productAbstractEntity, $localeTransfer));
        }

        foreach ($productAbstractEntity->getSpyProducts() as $productEntity) {
            $rawProductAttributesTransfer->setConcreteAttributes(array_merge(
                $rawProductAttributesTransfer->getConcreteAttributes(),
                $this->getConcreteAttributes($productEntity)
            ));

            if ($localeTransfer) {
                $rawProductAttributesTransfer->setConcreteLocalizedAttributes(array_merge(
                    $rawProductAttributesTransfer->getConcreteLocalizedAttributes(),
                    $this->getConcreteLocalizedAttributes($productEntity, $localeTransfer)
                ));
            }
        }

        $attributeKeys = array_keys($this->attributeMerger->merge($rawProductAttributesTransfer));

        return $attributeKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(ProductConcreteTransfer $productConcreteTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $rawProductAttributesTransfer = new RawProductAttributesTransfer();

        $productEntity = $this->getProductEntity($productConcreteTransfer);
        $productAbstractEntity = $productEntity->getSpyProductAbstract();

        $rawProductAttributesTransfer->setAbstractAttributes($this->getAbstractAttributes($productAbstractEntity));
        if ($localeTransfer) {
            $rawProductAttributesTransfer->setAbstractLocalizedAttributes($this->getAbstractLocalizedAttributes($productAbstractEntity, $localeTransfer));
        }

        $rawProductAttributesTransfer->setConcreteAttributes($this->getConcreteAttributes($productEntity));
        if ($localeTransfer) {
            $rawProductAttributesTransfer->setConcreteLocalizedAttributes($this->getConcreteLocalizedAttributes($productEntity, $localeTransfer));
        }

        return $this->attributeMerger->merge($rawProductAttributesTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getAbstractAttributes(SpyProductAbstract $productAbstractEntity)
    {
        return $this->attributeEncoder->decodeAttributes($productAbstractEntity->getAttributes());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function getConcreteAttributes(SpyProduct $productEntity)
    {
        return $this->attributeEncoder->decodeAttributes($productEntity->getAttributes());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getAbstractLocalizedAttributes(SpyProductAbstract $productAbstractEntity, LocaleTransfer $localeTransfer)
    {
        $localeTransfer->requireIdLocale();

        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $productAbstractLocalizedAttributesEntity) {
            if ($productAbstractLocalizedAttributesEntity->getFkLocale() !== $localeTransfer->getIdLocale()) {
                continue;
            }

            return $this->attributeEncoder->decodeAttributes($productAbstractLocalizedAttributesEntity->getAttributes());
        }

        return [];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getConcreteLocalizedAttributes(SpyProduct $productEntity, LocaleTransfer $localeTransfer)
    {
        $localeTransfer->requireIdLocale();

        foreach ($productEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            if ($productLocalizedAttributesEntity->getFkLocale() !== $localeTransfer->getIdLocale()) {
                continue;
            }

            return $this->attributeEncoder->decodeAttributes($productLocalizedAttributesEntity->getAttributes());
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntity(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->requireIdProductAbstract();

        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->findOneByIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        if (!$productAbstractEntity) {
            throw new MissingProductException(sprintf(
                'Abstract product %d not found!',
                $productAbstractTransfer->getIdProductAbstract()
            ));
        }

        return $productAbstractEntity;
    }

    /**
     * @param int[] $productIds
     *
     * @return array|mixed|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductAbstractEntitiesFromIds($productIds)
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->findPks($productIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductEntity(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireIdProductConcrete();

        $productEntity = $this->productQueryContainer
            ->queryProduct()
            ->findOneByIdProduct($productConcreteTransfer->getIdProductConcrete());

        if (!$productEntity) {
            throw new MissingProductException(sprintf(
                'Concrete product %d not found!',
                $productConcreteTransfer->getIdProductConcrete()
            ));
        }
        return $productEntity;
    }
}
