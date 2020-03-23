<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\PageData;

use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface;

class ProductPageDataTransferExpander implements ProductPageDataTransferExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface $productLabelFacade
     */
    public function __construct(ProductLabelSearchToProductLabelInterface $productLabelFacade)
    {
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setIdProductAbstracts($loadTransfer->getProductAbstractIds());

        $productLabelTransfers = $this->productLabelFacade
            ->getActiveLabelsByCriteria($productLabelCriteriaTransfer);

        $productLabelIdsByIdProductAbstractMap = $this->getProductLabelIdsByIdProductAbstractAndStoreNameMap($productLabelTransfers);

        $updatedPayloadTransfers = $this->updatePayloadTransfers(
            $loadTransfer->getPayloadTransfers(),
            $productLabelIdsByIdProductAbstractMap
        );

        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransfers
     *
     * @return array
     */
    protected function getProductLabelIdsByIdProductAbstractAndStoreNameMap(array $productLabelTransfers): array
    {
        $map = [];
        foreach ($productLabelTransfers as $productLabelTransfer) {
            foreach ($productLabelTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                foreach ($productLabelTransfer->getProductLabelProductAbstracts() as $productLabelProductAbstract) {
                    $map[$productLabelProductAbstract->getFkProductAbstract()][$storeTransfer->getName()][] = $productLabelTransfer->getIdProductLabel();
                }
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
            $labelIdsGroupedByProductAbstractIdAndStoreName = $productLabelIdsByIdProductAbstractMap[$payloadTransfer->getIdProductAbstract()] ?? [];

            $payloadTransfer->setLabelIds($labelIdsGroupedByProductAbstractIdAndStoreName);
        }

        return $payloadTransfers;
    }
}
