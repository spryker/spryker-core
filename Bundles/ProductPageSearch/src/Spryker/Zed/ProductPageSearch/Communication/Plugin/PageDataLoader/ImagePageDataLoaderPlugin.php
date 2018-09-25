<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 */
class ImagePageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * @api
     *
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
     * @param array $productAbstractIds
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     *
     * @return array
     */
    protected function setProductImages(array $productAbstractIds, array $payloadTransfers): array
    {
        $query = $this->getQueryContainer()->queryAllProductImageSetsByProductAbstractIds($productAbstractIds);

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
