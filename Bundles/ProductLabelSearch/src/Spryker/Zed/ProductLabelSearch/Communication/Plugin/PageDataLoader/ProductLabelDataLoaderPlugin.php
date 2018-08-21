<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface getRepository()()
 */
class ProductLabelDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
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
        $productLabelEntityTransfers = $this->getProductLabelsByIdProductAbstractIn($loadTransfer->getProductAbstractIds());
        $productLabelIdsByIdProductAbstractMap = $this->getProductLabelIdsByIdProductAbstractMap($productLabelEntityTransfers);

        $updatedPayloadTransfers = $this->updatePayloadTransfers(
            $loadTransfer->getPayloadTransfers(),
            $productLabelIdsByIdProductAbstractMap
        );

        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductLabelEntityTransfer[]
     */
    protected function getProductLabelsByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->getRepository()->getProductLabelsByIdProductAbstractIn($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer[] $productLabelEntityTransfers
     *
     * @return \Generated\Shared\Transfer\SpyProductLabelEntityTransfer[][]
     */
    protected function getProductLabelIdsByIdProductAbstractMap(array $productLabelEntityTransfers): array
    {
        $map = [];
        foreach ($productLabelEntityTransfers as $productLabelEntityTransfer) {
            foreach ($productLabelEntityTransfer->getSpyProductLabelProductAbstracts() as $productLabelProductAbstract) {
                $map[$productLabelProductAbstract->getFkProductAbstract()][] = $productLabelEntityTransfer->getIdProductLabel();
            }
        }

        return $map;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $productLabelIdsByIdProductAbstractMap
     *
     * @return array
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $productLabelIdsByIdProductAbstractMap): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $labelIds = $productLabelIdsByIdProductAbstractMap[$payloadTransfer->getIdProductAbstract()] ?? [];

            $payloadTransfer->setLabelIds($labelIds);
        }

        return $payloadTransfers;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductPageType()
    {
        return 'label';
    }
}
