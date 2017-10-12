<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Image;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductSet\Business\Exception\MissingProductImageSetException;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetImageReader implements ProductSetImageReaderInterface
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
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function findProductSetImageSets($idProductSet)
    {
        $productImageSets = [];

        $productImageSetCollection = $this->productSetQueryContainer
            ->queryProductImageSet($idProductSet)
            ->find();

        foreach ($productImageSetCollection as $productImageSetEntity) {
            $productImageSets[] = $this->getProductImageSetTransfer($productImageSetEntity);
        }

        return $productImageSets;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @throws \Spryker\Zed\ProductSet\Business\Exception\MissingProductImageSetException
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    protected function getProductImageSetTransfer(SpyProductImageSet $productImageSetEntity)
    {
        $productImageSetTransfer = $this->productImageFacade->findProductImageSetById($productImageSetEntity->getIdProductImageSet());

        if (!$productImageSetTransfer) {
            throw new MissingProductImageSetException(sprintf(
                'Missing product image set #%d.',
                $productImageSetEntity->getIdProductImageSet()
            ));
        }

        return $productImageSetTransfer;
    }
}
