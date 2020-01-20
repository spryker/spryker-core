<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository;
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
        $merchantProductAbstractData = $this->getRepository()
            ->getMerchantDataByProductAbstractIds($productPageLoadTransfer->getProductAbstractIds());

        $groupedMerchantNamesByIdProductAbstract = $this->groupMerchantNamesByIdProductAbstract($merchantProductAbstractData);

        $payloadTransfers = $this->updatePayloadTransfers($productPageLoadTransfer->getPayloadTransfers(), $groupedMerchantNamesByIdProductAbstract);

        return $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @param array $merchantProductAbstractData
     *
     * @return array
     */
    protected function groupMerchantNamesByIdProductAbstract(array $merchantProductAbstractData): array
    {
        $productAbstractIdMerchantNamesMap = [];
        foreach ($merchantProductAbstractData as $row) {
            $productAbstractIdMerchantNamesMap[$row[MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID]][] = $row[MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME];
        }

        return $productAbstractIdMerchantNamesMap;
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
