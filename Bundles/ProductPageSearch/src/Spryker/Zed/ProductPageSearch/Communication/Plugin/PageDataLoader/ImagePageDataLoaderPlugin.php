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
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
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
     * @param int[] $productAbstractIds
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     *
     * @return array
     */
    protected function setProductImages(array $productAbstractIds, array $payloadTransfers): array
    {
        [$imageSets, $defaultProductImageSets] = $this->getImageSets($productAbstractIds);
        $imageSets = $this->setDefaultImageSetsToMissedImageSets($imageSets, $defaultProductImageSets);

        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($imageSets[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $images = $imageSets[$payloadTransfer->getIdProductAbstract()];
            $payloadTransfer->setImages($images);
        }

        return $payloadTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array[]
     */
    protected function getImageSets(array $productAbstractIds): array
    {
        $imageSetCollection = $this->getQueryContainer()
            ->queryAllProductImageSetsByProductAbstractIds($productAbstractIds)
            ->find();

        $imageSets = [];
        $defaultProductImageSets = [];

        foreach ($imageSetCollection as $imageSetItem) {
            $imageSets[$imageSetItem->getFkProductAbstract()][$imageSetItem->getFkLocale()][] = $imageSetItem;

            if ($imageSetItem->getFkLocale() !== null) {
                continue;
            }

            $defaultProductImageSets[$imageSetItem->getFkProductAbstract()][] = $imageSetItem;
        }

        return [
            $imageSets,
            $defaultProductImageSets,
        ];
    }

    /**
     * @param array[] $imageSets
     * @param array[] $defaultProductImageSets
     *
     * @return array[]
     */
    protected function setDefaultImageSetsToMissedImageSets(array $imageSets, array $defaultProductImageSets): array
    {
        $productAbstractIds = array_keys($imageSets);

        $abstractProducts = $this->getQueryContainer()
            ->queryProductAbstractWithLocalizedAttributesByIds($productAbstractIds)
            ->find();

        foreach ($abstractProducts as $abstractProduct) {
            $productAbstractId = $abstractProduct->getFkProductAbstract();
            $localeId = $abstractProduct->getFkLocale();

            if (isset($imageSets[$productAbstractId][$localeId])) {
                continue;
            }

            $imageSets[$productAbstractId][$localeId] = $defaultProductImageSets[$productAbstractId];
        }

        return $imageSets;
    }
}
