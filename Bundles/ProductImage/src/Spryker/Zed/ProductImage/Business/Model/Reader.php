<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Reader implements ReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

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
        $this->productImageContainer = $productImageContainer;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        $imageCollection = $this->productImageContainer
            ->queryImageSetByProductAbstractId($idProductAbstract)
            ->find();

        return $this->transferMapper->mapProductImageSetCollection($imageCollection);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        $imageCollection = $this->productImageContainer
            ->queryImageSetByProductId($idProduct)
            ->find();

        return $this->transferMapper->mapProductImageSetCollection($imageCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithImageSets(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetCollection = $this->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract()
        );

        if ($imageSetCollection === null) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setImageSets(new ArrayObject($imageSetCollection));

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithImageSets(ProductConcreteTransfer $productConcreteTransfer)
    {
        $imageSetCollection = $this->getProductImagesSetCollectionByProductId(
            $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete()
        );

        if ($imageSetCollection === null) {
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->setImageSets(new ArrayObject($imageSetCollection));

        return $productConcreteTransfer;
    }

    /**
     * @param $idProductAbstract
     * @param $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedAbstractImageSets($idProductAbstract, $idLocale)
    {
        $abstractDefaultImageSets = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale(null)
            ->find();

        $abstractLocalizedImageSets = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale($idLocale)
            ->find();

        return $this->getImageSetsIndexedByName($abstractLocalizedImageSets)
            + $this->getImageSetsIndexedByName($abstractDefaultImageSets);
    }

    /**
     * @param $idProductConcrete
     * @param $idProductAbstract
     * @param $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale)
    {
        $concreteDefaultImageSets = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale(null)
            ->find();

        $concreteLocalizedImageSets = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale($idLocale)
            ->find();

        return $this->getImageSetsIndexedByName($concreteLocalizedImageSets)
            + $this->getImageSetsIndexedByName($concreteDefaultImageSets)
            + $this->getCombinedAbstractImageSets($idProductAbstract, $idLocale);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $imageSets
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getImageSetsIndexedByName(ObjectCollection $imageSets)
    {
        $result = [];

        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $imageSetEntity */
        foreach ($imageSets as $imageSetEntity) {
            $imageSetTransfer = $this->transferMapper->mapProductImageSet($imageSetEntity);
            $result[$imageSetEntity->getName()] = $imageSetTransfer;
        }

        return $result;
    }

}
