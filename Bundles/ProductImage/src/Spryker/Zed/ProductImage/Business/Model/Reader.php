<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGeneratorInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Reader implements ReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @var \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(
        ProductImageQueryContainerInterface $productImageContainer,
        ProductImageTransferGeneratorInterface $transferGenerator
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

}
