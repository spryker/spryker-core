<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductImage\Persistence\Base\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataLoaderPluginInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ImagePageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{

    /**
     * @param ProductPageLoadTransfer $loadTransfer
     *
     * @return ProductPageLoadTransfer
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
     * @param ProductPayloadTransfer[]  $payloadTransfers
     *
     * @return array
     */
    protected function setProductImages(array $productAbstractIds, array $payloadTransfers): array
    {
        $query = SpyProductImageSetQuery::create()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductImageSetToProductImage()
            ->joinWith('SpyProductImageSetToProductImage.SpyProductImage')
        ;

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
