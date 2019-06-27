<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;
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
     * @var \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     * @param \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface $transferMapper
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductImageQueryContainerInterface $productImageContainer,
        ProductImageTransferMapperInterface $transferMapper,
        ProductImageToLocaleInterface $localeFacade
    ) {
        $this->productImageContainer = $productImageContainer;
        $this->transferMapper = $transferMapper;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetCollection */
        $productImageSetCollection = $this->productImageContainer
            ->queryImageSetByProductAbstractId($idProductAbstract)
            ->find();

        return $this->transferMapper->mapProductImageSetCollection($productImageSetCollection);
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
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();

        $imageCollection = $this->productImageContainer
            ->queryLocalizedConcreteProductImageSets($idProduct, $idLocale)
            ->find();

        if ($imageCollection->count() === 0) {
            $imageCollection = $this->productImageContainer
                ->queryDefaultConcreteProductImageSets($idProduct)
                ->find();
        }

        return $this->transferMapper->mapProductImageSetCollection($imageCollection);
    }

    /**
     * @param int $idProductImageSet
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    public function findProductImagesSetCollectionById($idProductImageSet)
    {
        $productImageSetEntity = $this->productImageContainer
            ->queryImageSetById($idProductImageSet)
            ->findOne();

        if (!$productImageSetEntity) {
            return null;
        }

        return $this->transferMapper->mapProductImageSet($productImageSetEntity);
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

        if (!$imageSetCollection) {
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
        $productImageSetCollection = $this->getProductImagesSetCollectionByProductId(
            $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete()
        );

        if (!$productImageSetCollection) {
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->setImageSets(new ArrayObject($productImageSetCollection));

        return $productConcreteTransfer;
    }
}
