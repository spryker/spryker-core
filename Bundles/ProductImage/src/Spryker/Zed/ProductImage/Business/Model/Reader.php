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
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(
        ProductImageQueryContainerInterface $productImageContainer,
        ProductImageTransferMapperInterface $transferGenerator
    ) {
        $this->productImageContainer = $productImageContainer;
        $this->transferGenerator = $transferGenerator;
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

        return $this->transferGenerator->convertProductImageSetCollection($imageCollection);
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

        return $this->transferGenerator->convertProductImageSetCollection($imageCollection);
    }


    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function runProductAbstractReadPlugin(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetCollection = $this->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->getIdProductAbstract()
        );

        if ($imageSetCollection === null) {
            return;
        }

        $productAbstractTransfer->setImageSets(
            new ArrayObject($imageSetCollection)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function runProductConcreteReadPlugin(ProductConcreteTransfer $productConcreteTransfer)
    {
        $imageSetCollection = $this->getProductImagesSetCollectionByProductId(
            $productConcreteTransfer->getIdProductConcrete()
        );

        if ($imageSetCollection === null) {
            return;
        }

        $productConcreteTransfer->setImageSets(
            new ArrayObject($imageSetCollection)
        );
    }

}
