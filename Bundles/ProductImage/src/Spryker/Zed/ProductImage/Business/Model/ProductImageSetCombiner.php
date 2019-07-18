<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class ProductImageSetCombiner implements ProductImageSetCombinerInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageQueryContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     * @param \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface $transferMapper
     */
    public function __construct(
        ProductImageQueryContainerInterface $productImageContainer,
        ProductImageTransferMapperInterface $transferMapper
    ) {
        $this->productImageQueryContainer = $productImageContainer;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedAbstractImageSets($idProductAbstract, $idLocale)
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $abstractDefaultImageSets */
        $abstractDefaultImageSets = $this->productImageQueryContainer
            ->queryDefaultAbstractProductImageSets($idProductAbstract)
            ->find();

        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $abstractLocalizedImageSets */
        $abstractLocalizedImageSets = $this->productImageQueryContainer
            ->queryLocalizedAbstractProductImageSets($idProductAbstract, $idLocale)
            ->find();

        return $this->getImageSetsIndexedByName($abstractLocalizedImageSets)
            + $this->getImageSetsIndexedByName($abstractDefaultImageSets);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale)
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $concreteDefaultImageSets */
        $concreteDefaultImageSets = $this->productImageQueryContainer
            ->queryDefaultConcreteProductImageSets($idProductConcrete)
            ->find();

        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $concreteLocalizedImageSets */
        $concreteLocalizedImageSets = $this->productImageQueryContainer
            ->queryLocalizedConcreteProductImageSets($idProductConcrete, $idLocale)
            ->find();

        return $this->getImageSetsIndexedByName($concreteLocalizedImageSets)
            + $this->getImageSetsIndexedByName($concreteDefaultImageSets)
            + $this->getCombinedAbstractImageSets($idProductAbstract, $idLocale);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductImage\Persistence\SpyProductImageSet[] $imageSets
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getImageSetsIndexedByName(ObjectCollection $imageSets)
    {
        $result = [];

        foreach ($imageSets as $imageSetEntity) {
            $imageSetTransfer = $this->transferMapper->mapProductImageSet($imageSetEntity);
            $result[$imageSetEntity->getName()] = $imageSetTransfer;
        }

        return $result;
    }
}
