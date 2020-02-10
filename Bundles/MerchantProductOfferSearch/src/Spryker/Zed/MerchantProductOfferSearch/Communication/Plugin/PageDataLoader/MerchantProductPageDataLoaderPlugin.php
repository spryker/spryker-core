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
        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();

        $merchantNames = $this->getFacade()
            ->getMerchantNamesByProductAbstractIds($productAbstractIds);

        $merchantReferences = $this->getFacade()
            ->getMerchantReferencesByProductAbstractIds($productAbstractIds);

        $payloadTransfers = $this->setMerchantNamesToPayloadTransfers($productPageLoadTransfer->getPayloadTransfers(), $merchantNames);
        $payloadTransfers = $this->setMerchantReferencesToPayloadTransfers($payloadTransfers, $merchantReferences);

        return $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $groupedMerchantNamesByIdProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function setMerchantNamesToPayloadTransfers(array $payloadTransfers, array $groupedMerchantNamesByIdProductAbstract): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $merchantNames = $groupedMerchantNamesByIdProductAbstract[$payloadTransfer->getIdProductAbstract()] ?? [];
            $payloadTransfer->setMerchantNames($merchantNames);
        }

        return $payloadTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $groupedMerchantReferencesByIdProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[]
     */
    protected function setMerchantReferencesToPayloadTransfers(array $payloadTransfers, array $groupedMerchantReferencesByIdProductAbstract): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $merchantReferences = $groupedMerchantReferencesByIdProductAbstract[$payloadTransfer->getIdProductAbstract()] ?? [];
            $payloadTransfer->setMerchantReferences($merchantReferences);
        }

        return $payloadTransfers;
    }
}
