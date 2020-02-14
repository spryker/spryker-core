<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class MerchantProductPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductPageLoadTransfer object with merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $productPageLoadTransfer)
    {
        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();

        $merchantProductAbstractTransfers = $this->getFacade()
            ->getMerchantProductAbstractsByProductAbstractIds($productAbstractIds);

        return $this->setMerchantsToPayloadTransfers($productPageLoadTransfer, $merchantProductAbstractTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param \Generated\Shared\Transfer\MerchantProductAbstractTransfer[] $merchantProductAbstractTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    protected function setMerchantsToPayloadTransfers(
        ProductPageLoadTransfer $productPageLoadTransfer,
        array $merchantProductAbstractTransfers
    ): ProductPageLoadTransfer {
        $updatedPayLoadTransfers = [];

        foreach ($productPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $updatedPayLoadTransfers[$payloadTransfer->getIdProductAbstract()] = $this->setMerchantToPayloadTransfer($payloadTransfer, $merchantProductAbstractTransfers);
        }

        return $productPageLoadTransfer->setPayloadTransfers($updatedPayLoadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer
     * @param \Generated\Shared\Transfer\MerchantProductAbstractTransfer[] $merchantProductAbstractTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function setMerchantToPayloadTransfer(
        ProductPayloadTransfer $payloadTransfer,
        array $merchantProductAbstractTransfers
    ): ProductPayloadTransfer {
        foreach ($merchantProductAbstractTransfers as $merchantProductAbstractTransfer) {
            if ($payloadTransfer->getIdProductAbstract() !== $merchantProductAbstractTransfer->getIdProductAbstract()) {
                continue;
            }

            $payloadTransfer->setMerchantNames($merchantProductAbstractTransfer->getMerchantNames());
            $payloadTransfer->setMerchantReferences($merchantProductAbstractTransfer->getMerchantReferences());
        }

        return $payloadTransfer;
    }
}
