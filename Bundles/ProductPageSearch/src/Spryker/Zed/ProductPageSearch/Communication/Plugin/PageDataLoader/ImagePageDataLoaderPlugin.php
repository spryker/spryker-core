<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 */
class ImagePageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $payloadTransfers = $this->setProductImages($loadTransfer->getProductAbstractIds(), $loadTransfer->getPayloadTransfers());
        $loadTransfer->setPayloadTransfers($payloadTransfers);

        return $loadTransfer;
    }

    /**
     * @return string
     */
    public function getProductPageType()
    {
        return 'image';
    }

    /**
     * @param array $productAbstractIds
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     *
     * @return array
     */
    protected function setProductImages(array $productAbstractIds, array $payloadTransfers): array
    {
        // TODO move this to QueryContainer or create Repository
        $query = SpyProductImageSetQuery::create()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductImageSetToProductImage()
            ->joinWith('SpyProductImageSetToProductImage.SpyProductImage');

        $imageSets = [];
        $imageSetCollection = $query->find();
        foreach ($imageSetCollection as $imageSetItem) {
            $imageSets[$imageSetItem->getFkProductAbstract()][$imageSetItem->getFkLocale()][] = $imageSetItem;
        }

        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($imageSets[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $images = $imageSets[$payloadTransfer->getIdProductAbstract()];
            $payloadTransfer->setImages($images);
        }

        return $payloadTransfers;
    }
}
