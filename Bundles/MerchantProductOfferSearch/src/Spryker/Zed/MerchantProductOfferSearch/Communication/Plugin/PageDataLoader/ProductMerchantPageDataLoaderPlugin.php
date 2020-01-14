<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\MerchantMapTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class ProductMerchantPageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductPageLoadTransfer object with merchant names.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $merchantProductAbstractData = $this->getRepository()
            ->getMerchantProductAbstractDataByProductAbstractIds($loadTransfer->getProductAbstractIds());

        $productAbstractIdMerchantNamesMap = $this->mapProductAbstractIdToMerchantNames($merchantProductAbstractData);

        $payloadTransfers = $this->updatePayloadTransfers($loadTransfer->getPayloadTransfers(), $productAbstractIdMerchantNamesMap);

        return $loadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mapProductAbstractIdToMerchantNames(array $data): array
    {
        $productAbstractIdMerchantNamesMap = [];
        foreach ($data as $row) {
            $productAbstractIdMerchantNamesMap[$row[MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID]][MerchantMapTransfer::NAMES][] = $row[MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME];
        }

        return $productAbstractIdMerchantNamesMap;
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

            $payloadTransfer->setMerchants($productAbstractIdMerchantNamesMap[$payloadTransfer->getIdProductAbstract()]);
        }

        return $payloadTransfers;
    }
}
