<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table\Manager;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface;

class ProductAbstractTableManager implements ProductAbstractTableManagerInterface
{
    /**
     * @var \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface $productImageFacade
     */
    public function __construct(ContentProductGuiToProductImageInterface $productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    public function getAbstractProductStatus(SpyProductAbstract $productAbstractEntity): bool
    {
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    public function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity): ?string
    {
        $productImageSetTransferCollection = $this->productImageFacade
            ->getProductImagesSetCollectionByProductAbstractId($productAbstractEntity->getIdProductAbstract());

        foreach ($productImageSetTransferCollection as $productImageSetTransfer) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                if ($previewUrl = $productImageTransfer->getExternalUrlSmall()) {
                    return $previewUrl;
                }
            }
        }

        return null;
    }
}
