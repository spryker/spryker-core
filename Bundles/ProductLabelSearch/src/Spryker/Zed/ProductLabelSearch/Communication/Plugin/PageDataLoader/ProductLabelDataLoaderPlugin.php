<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\PageDataLoader;

use DateTime;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\SpyProductLabelEntityTransfer;
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
     * @return array
     */
    protected function getProductLabelIdsByIdProductAbstractMap(array $productLabelEntityTransfers): array
    {
        $map = [];
        foreach ($productLabelEntityTransfers as $productLabelEntityTransfer) {
            foreach ($productLabelEntityTransfer->getSpyProductLabelProductAbstracts() as $productLabelProductAbstract) {
                if (!$this->isValidByDate($productLabelEntityTransfer)) {
                    continue;
                }

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
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer $spyProductLabel
     *
     * @return bool
     */
    protected function isValidByDate(SpyProductLabelEntityTransfer $spyProductLabel)
    {
        $isValidFromDate = $this->isValidByDateFrom($spyProductLabel);
        $isValidToDate = $this->isValidByDateTo($spyProductLabel);

        return ($isValidFromDate && $isValidToDate);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer $productLabel
     *
     * @return bool
     */
    protected function isValidByDateFrom(SpyProductLabelEntityTransfer $productLabel)
    {
        if (!$productLabel->getValidFrom()) {
            return true;
        }

        $now = new DateTime();

        if ($now < $productLabel->getValidFrom()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer $productLabel
     *
     * @return bool
     */
    protected function isValidByDateTo(SpyProductLabelEntityTransfer $productLabel)
    {
        if (!$productLabel->getValidTo()) {
            return true;
        }

        $now = new DateTime();

        if ($productLabel->getValidTo() < $now) {
            return false;
        }

        return true;
    }
}
