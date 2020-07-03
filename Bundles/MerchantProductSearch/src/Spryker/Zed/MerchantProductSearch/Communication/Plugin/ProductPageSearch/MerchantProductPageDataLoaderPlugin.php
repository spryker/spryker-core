<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductSearch\Business\MerchantProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 */
class MerchantProductPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductPageLoadTransfer object with merchant data.
     * - Merges merchant name from PayloadTransfer with merchant names from given merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();

        $productAbstractMerchantData = $this->getFacade()
            ->getMerchantDataByProductAbstractIds($productAbstractIds);

        return $this->setMerchantDataToPayloadTransfers($productPageLoadTransfer, $productAbstractMerchantData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[] $productAbstractMerchantData
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    protected function setMerchantDataToPayloadTransfers(
        ProductPageLoadTransfer $productPageLoadTransfer,
        array $productAbstractMerchantData
    ): ProductPageLoadTransfer {
        $updatedPayLoadTransfers = [];

        foreach ($productPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $updatedPayLoadTransfers[$payloadTransfer->getIdProductAbstract()] = $this->setMerchantDataToPayloadTransfer($payloadTransfer, $productAbstractMerchantData);
        }

        return $productPageLoadTransfer->setPayloadTransfers($updatedPayLoadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[] $productAbstractMerchantData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function setMerchantDataToPayloadTransfer(
        ProductPayloadTransfer $payloadTransfer,
        array $productAbstractMerchantData
    ): ProductPayloadTransfer {
        foreach ($productAbstractMerchantData as $productAbstractMerchantTransfer) {
            if ($payloadTransfer->getIdProductAbstract() !== $productAbstractMerchantTransfer->getIdProductAbstract()) {
                continue;
            }

            $merchantNames = $this->mergeMerchantNames($payloadTransfer, $productAbstractMerchantTransfer);
            $payloadTransfer->setMerchantNames($merchantNames);
        }

        return $payloadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantTransfer $productAbstractMerchantTransfer
     *
     * @return array
     */
    protected function mergeMerchantNames(ProductPayloadTransfer $payloadTransfer, ProductAbstractMerchantTransfer $productAbstractMerchantTransfer): array
    {
        $merchantNames = $payloadTransfer->getMerchantNames();
        foreach ($productAbstractMerchantTransfer->getMerchantNames() as $store => $names) {
            if (array_key_exists($store, $merchantNames)) {
                $merchantNames[$store] = array_unique(array_merge($merchantNames[$store], $names));

                continue;
            }

            $merchantNames[$store] = $names;
        }

        return $merchantNames;
    }
}
