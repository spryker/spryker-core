<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductVariantTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Spryker\Shared\Library\Json;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductVariantBuilder implements ProductVariantBuilderInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer[]
     */
    public function getProductVariantsByAbstractSku($abstractSku)
    {
        $abstractProduct = $this->productQueryContainer
            ->queryProductAbstractBySku($abstractSku)
            ->findOne();

        if (!$abstractProduct) {
            return [];
        }

        $abstractProductVariants = $this->createAbstractProductVariants($abstractProduct);
        $productVariants = $this->createConcreteProductVariants($abstractProduct, $abstractProductVariants);

        return $productVariants;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     *
     * @return array
     */
    protected function createAbstractProductVariants(SpyProductAbstract $abstractProductEntity)
    {
        $abstractProductAttributes = Json::decode($abstractProductEntity->getAttributes(), true);
        $abstractLocalizedAttributes = $abstractProductEntity->getSpyProductAbstractLocalizedAttributess();

        $abstractProductVariants = [];
        foreach ($abstractLocalizedAttributes as $localizedAttributeEntity) {
            $productVariantTransfer = $this->hydrateAbstractProductVariant(
                $abstractProductEntity,
                $localizedAttributeEntity,
                $abstractProductAttributes
            );

            $abstractProductVariants[$localizedAttributeEntity->getFkLocale()] = $productVariantTransfer;
        }

        return $abstractProductVariants;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProduct
     * @param array $abstractProductVariants
     *
     * @return array
     */
    protected function createConcreteProductVariants(
        SpyProductAbstract $abstractProduct,
        array $abstractProductVariants
    ) {
        $productVariants = [];
        $concreteProducts = $abstractProduct->getSpyProducts();
        foreach ($concreteProducts as $concreteProductEntity) {
            $concreteProductVariants = $this->createConcreteProductLocalizedVariants(
                $abstractProductVariants,
                $concreteProductEntity
            );
            $productVariants = array_merge($productVariants, $concreteProductVariants);
        }
        return $productVariants;
    }

    /**
     * @param array $abstractProductVariants
     * @param \Orm\Zed\Product\Persistence\SpyProduct $concreteProductEntity
     *
     * @return array
     */
    protected function createConcreteProductLocalizedVariants(
        array $abstractProductVariants,
        SpyProduct $concreteProductEntity
    ) {

        $productVariants = [];
        $concreteProductAttributes = Json::decode($concreteProductEntity->getAttributes(), true);
        $concreteLocalizedConcreteProductAttributes = $concreteProductEntity->getSpyProductLocalizedAttributess();

        foreach ($concreteLocalizedConcreteProductAttributes as $localizedAttributeEntity) {
            $productVariantTransfer = $this->getProductVariantTransfer(
                $abstractProductVariants,
                $localizedAttributeEntity
            );
            $mergedAbstractAttributes = $productVariantTransfer->getAttributes();

            $abstractProduct = $concreteProductEntity->toArray();
            unset($abstractProduct['attributes']);
            $productVariantTransfer->fromArray($abstractProduct, true);

            $localizedAttributes = $localizedAttributeEntity->toArray();
            unset($localizedAttributes['attributes']);
            $productVariantTransfer->fromArray($localizedAttributes, true);

            $localizedConcreteProductAttributes = Json::decode($localizedAttributeEntity->getAttributes(), true);

            $concreteMergedAttributes = array_merge(
                $mergedAbstractAttributes,
                $concreteProductAttributes,
                $localizedConcreteProductAttributes
            );

            $productVariantTransfer->setAttributes($concreteMergedAttributes);
            $productVariants[] = $productVariantTransfer;

        }
        return $productVariants;
    }


    /**
     * @param array $abstractProductVariants
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductLocalizedAttributes $localizedAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer
     */
    protected function getProductVariantTransfer(
        array $abstractProductVariants,
        SpyProductLocalizedAttributes $localizedAttributeEntity
    ) {

        if (isset($abstractProductVariants[$localizedAttributeEntity->getFkLocale()])) {
            /* @var $productVariantTransfer ProductVariantTransfer */
            $productVariantTransfer = clone $abstractProductVariants[$localizedAttributeEntity->getFkLocale()];
        } else {
            $productVariantTransfer = new ProductVariantTransfer();
            $productVariantTransfer->setLocaleName($localizedAttributeEntity->getLocale()->getLocaleName());
        }

        return $productVariantTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $localizedAttributeEntity
     * @param array $abstractProductAttributes
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer
     */
    protected function hydrateAbstractProductVariant(
        SpyProductAbstract $abstractProductEntity,
        SpyProductAbstractLocalizedAttributes $localizedAttributeEntity,
        array $abstractProductAttributes
    ) {
        $productVariantTransfer = new ProductVariantTransfer();

        $abstractProduct = $abstractProductEntity->toArray();
        unset($abstractProduct['attributes']);
        $productVariantTransfer->fromArray($abstractProduct, true);

        $localizedAttributes = $localizedAttributeEntity->toArray();
        unset($localizedAttributes['attributes']);
        $productVariantTransfer->fromArray($localizedAttributes, true);








        $productVariantTransfer->setLocaleName($localizedAttributeEntity->getLocale()->getLocaleName());

        $localizedAttributes = array_merge(
            $abstractProductAttributes,
            Json::decode($localizedAttributeEntity->getAttributes(), true)
        );
        $productVariantTransfer->setAttributes($localizedAttributes);

        return $productVariantTransfer;
    }

}
