<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Image;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetImageSetCombiner implements ProductSetImageSetCombinerInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface $productImageFacade
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer, ProductSetToProductImageInterface $productImageFacade)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedProductSetImageSets($idProductSet, $idLocale)
    {
        $defaultProductImageSetEntities = $this->findDefaultProductImageSets($idProductSet);
        $localizedProductImageSetEntities = $this->findLocalizedProductImageSets($idProductSet, $idLocale);
        $combinedProductImageSetEntities = $this->combineProductImageSets($defaultProductImageSetEntities, $localizedProductImageSetEntities);

        return $this->mapProductImageSets($combinedProductImageSetEntities);
    }

    /**
     * @param int $idProductSet
     *
     * @return array
     */
    protected function findDefaultProductImageSets($idProductSet)
    {
        return $this->productSetQueryContainer
            ->queryDefaultProductImageSet($idProductSet)
            ->find()
            ->toKeyIndex('name');
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return array
     */
    protected function findLocalizedProductImageSets($idProductSet, $idLocale)
    {
        return $this->productSetQueryContainer
            ->queryProductImageSet($idProductSet, $idLocale)
            ->find()
            ->toKeyIndex('name');
    }

    /**
     * @param array $defaultProductImageSetEntities
     * @param array $localizedProductImageSetEntities
     *
     * @return array
     */
    protected function combineProductImageSets(array $defaultProductImageSetEntities, array $localizedProductImageSetEntities)
    {
        return array_replace($defaultProductImageSetEntities, $localizedProductImageSetEntities);
    }

    /**
     * @param array $combinedProductImageSetEntities
     *
     * @return array
     */
    protected function mapProductImageSets(array $combinedProductImageSetEntities)
    {
        $productImageSetTransfers = [];
        foreach ($combinedProductImageSetEntities as $productImageSetEntity) {
            $productImageSetTransfers[] = $this->getProductImageSet($productImageSetEntity);
        }

        return $productImageSetTransfers;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected function getProductImageSet(SpyProductImageSet $productImageSetEntity)
    {
        return $this->productImageFacade->findProductImageSetById($productImageSetEntity->getIdProductImageSet());
    }
}
