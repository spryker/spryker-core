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
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class MerchantProductPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductPageLoadTransfer object with merchant names.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $productPageLoadTransfer)
    {
        $merchantNames = $this->getFacade()
            ->getMerchantNamesByProductAbstractIds($productPageLoadTransfer->getProductAbstractIds());
        $payloadTransfers = $this->updatePayloadTransfers($productPageLoadTransfer->getPayloadTransfers(), $merchantNames);

        return $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $groupedMerchantNamesByIdProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $groupedMerchantNamesByIdProductAbstract): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $merchantNames = $groupedMerchantNamesByIdProductAbstract[$payloadTransfer->getIdProductAbstract()] ?? [];
            $payloadTransfer->setMerchantNames($merchantNames);
        }

        return $payloadTransfers;
    }
}
