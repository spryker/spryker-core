<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Communication\MerchantProductOfferSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class ProductPageDataLoaderMerchantPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $productAbstractIdMerchantNamesMap = $this->getRepository()
            ->getProductAbstractIdMerchantNamesMapByProductAbstractIds($loadTransfer->getProductAbstractIds());

        $updatedPayloadTransfers = $this->updatePayloadTransfers($loadTransfer->getPayloadTransfers(), $productAbstractIdMerchantNamesMap);
        $loadTransfer->setPayloadTransfers($updatedPayloadTransfers);

        return $loadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $productAbstractIdMerchantNamesMap
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $productAbstractIdMerchantNamesMap): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($productAbstractIdMerchantNamesMap[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $payloadTransfer->setMerchantNames($productAbstractIdMerchantNamesMap[$payloadTransfer->getIdProductAbstract()]);
        }

        return $payloadTransfers;
    }
}
