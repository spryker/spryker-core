<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacade getFacade()
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 */
class ProductReviewPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
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
        $productReviews = $this->getRepository()
            ->getProductReviewRatingByIdAbstractProductIn($loadTransfer->getProductAbstractIds());

        $updatedPayloadTransfers = $this->updatePayloadTransfers($loadTransfer->getPayloadTransfers(), $productReviews);
        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $productReviewsList
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $productReviewsList): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $productReviews = $productReviewsList[$payloadTransfer->getIdProductAbstract()] ?? [];

            $payloadTransfer->fromArray($productReviews, true);
        }

        return $payloadTransfers;
    }
}
