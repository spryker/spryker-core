<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table\Helper;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface;

class ProductAbstractTableHelper implements ProductAbstractTableHelperInterface
{
    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageInterface $productImageFacade
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyInterface $moneyFacade
     */
    public function __construct(
        ProductSetGuiToProductImageInterface $productImageFacade,
        ProductSetGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductSetGuiToMoneyInterface $moneyFacade
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    public function getProductPreview(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<img src="%s">',
            $this->getProductPreviewUrl($productAbstractEntity)
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    public function getProductPrice(SpyProductAbstract $productAbstractEntity)
    {
        $price = $this->priceProductFacade->findPriceBySku($productAbstractEntity->getSku());
        if ($price === null) {
            return 'N/A';
        }
        $moneyTransfer = $this->moneyFacade->fromInteger($price);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    public function getAbstractProductStatusLabel(SpyProductAbstract $productAbstractEntity)
    {
        $isActive = false;
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                $isActive = true;
            }
        }

        return $this->getStatusLabel($isActive);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    protected function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity)
    {
        $productImageSetTransferCollection = $this->productImageFacade
            ->getProductImagesSetCollectionByProductAbstractId($productAbstractEntity->getIdProductAbstract());

        foreach ($productImageSetTransferCollection as $productImageSetTransfer) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $previewUrl = $productImageTransfer->getExternalUrlSmall();

                if ($previewUrl) {
                    return $previewUrl;
                }
            }
        }

        return null;
    }

    /**
     * @param bool $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        if (!$status) {
            return '<span class="label label-danger">Inactive</span>';
        }

        return '<span class="label label-info">Active</span>';
    }
}
