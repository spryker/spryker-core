<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
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
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $productPageLoadTransfer)
    {
        return $productPageLoadTransfer;

//        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();
//
//        $productAbstractMerchantData = $this->getFacade()
//            ->getProductAbstractMerchantDataByProductAbstractIds($productAbstractIds);
//
//        return $this->setMerchantDataToPayloadTransfers($productPageLoadTransfer, $productAbstractMerchantData);
    }
}
