<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(ProductImageQueryContainerInterface $productImageContainer)
    {
        $this->productImageContainer = $productImageContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function persistProductImage(ProductImageTransfer $productImageTransfer)
    {
        $query = $this->productImageContainer
            ->queryProductImage();

        if ($productImageTransfer->getIdProductImage()) {
            $query
                ->filterByIdProductImage($productImageTransfer->getIdProductImage());
        }

        $productImageEntity = $query->findOneOrCreate();
        $productImageEntity->fromArray($productImageTransfer->toArray());
        $productImageEntity->save();

        $productImageTransfer->setIdProductImage($productImageEntity->getIdProductImage());

        return $productImageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @throws \Exception
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $query = $this->productImageContainer
            ->queryProductImageSet();

        if ($productImageSetTransfer->getIdProductImageSet()) {
            $query
                ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet());
        }

        $this->productImageContainer->getConnection()->beginTransaction();
        try {
            $productImageSetEntity = $query->findOneOrCreate();
            $productImageSetEntity->fromArray($productImageSetTransfer->toArray());
            $productImageSetEntity->save();

            $productImageSetTransfer->setIdProductImageSet($productImageSetEntity->getIdProductImageSet());

            $updatedImageCollection = [];
            foreach ($productImageSetTransfer->getProductImages() as $imageTransfer) {
                $imageTransfer = $this->persistProductImage($imageTransfer);
                $updatedImageCollection[] = $imageTransfer;
            }

            $productImageSetTransfer->setProductImages(
                new \ArrayObject($updatedImageCollection)
            );

            $this->productImageContainer->getConnection()->commit();

            return $productImageSetTransfer;

        } catch (\Exception $e) {
            $this->productImageContainer->getConnection()->beginTransaction();
            throw $e;
        }
    }

}
