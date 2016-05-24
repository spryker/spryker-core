<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductVariantTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Shared\Library\Json;

class ProductVariant implements ProductVariantInterface
{
    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $abstractSku
     *
     * @return ProductVariantTransfer[]
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
     * @param SpyProductAbstract $abstractProductEntity
     *
     * @return array
     */
    protected function createAbstractProductVariants(SpyProductAbstract $abstractProductEntity)
    {
        $abstractProductAttributes = Json::decode($abstractProductEntity->getAttributes(), true);
        $abstractLocalizedAttributes = $abstractProductEntity->getSpyProductAbstractLocalizedAttributess();

        $abstractProductVariants = [];
        foreach ($abstractLocalizedAttributes as $localizedAttributeEntity) {
            $productVariantTransfer = new ProductVariantTransfer();
            $productVariantTransfer->fromArray($abstractProductEntity->toArray(), true);
            $productVariantTransfer->fromArray($localizedAttributeEntity->toArray(), true);
            $productVariantTransfer->setLocaleName($localizedAttributeEntity->getLocale()->getLocaleName());

            $localizedAttributes = array_merge(
                $abstractProductAttributes,
                Json::decode($localizedAttributeEntity->getAttributes(), true)
            );
            $productVariantTransfer->setAttributes($localizedAttributes);

            $abstractProductVariants[$localizedAttributeEntity->getFkLocale()] = $productVariantTransfer;
        }

        return $abstractProductVariants;
    }

    /**
     * @param SpyProductAbstract $abstractProduct
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
            $concreteProductAttributes = Json::decode($concreteProductEntity->getAttributes(), true);
            $concreteLocalizedConcreteProductAttributes = $concreteProductEntity->getSpyProductLocalizedAttributess();

            foreach ($concreteLocalizedConcreteProductAttributes as $localizedAttributeEntity) {
                $productVariantTransfer = $this->getProductVariantTransfer(
                    $abstractProductVariants,
                    $localizedAttributeEntity
                );
                $mergedAbstractAttributes = $productVariantTransfer->getAttributes();

                $productVariantTransfer->fromArray($concreteProductEntity->toArray(), true);
                $productVariantTransfer->fromArray($localizedAttributeEntity->toArray(), true);

                $localizedConcreteProductAttributes = Json::decode($localizedAttributeEntity->getAttributes(), true);

                $concreteMergedAttributes = array_merge(
                    $mergedAbstractAttributes,
                    $concreteProductAttributes,
                    $localizedConcreteProductAttributes
                );

                $productVariantTransfer->setAttributes($concreteMergedAttributes);
                $productVariants[] = $productVariantTransfer;

            }
        }
        return $productVariants;
    }

    /**
     * @param array $abstractProductVariants
     * @param SpyProductLocalizedAttributes $localizedAttributeEntity
     *
     * @return ProductVariantTransfer
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
}
