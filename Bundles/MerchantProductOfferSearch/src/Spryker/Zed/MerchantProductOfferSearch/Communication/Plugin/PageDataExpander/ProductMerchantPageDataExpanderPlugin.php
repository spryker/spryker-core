<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\MerchantMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchFacadeInterface getFacade()
 */
class ProductMerchantPageDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided ProductAbstractPageSearch transfer object's data by merchant names.
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productPayloadTransfer = $this->getProductPayloadTransfer($productData);

        if ($productPayloadTransfer->getMerchants()) {
            $this->expandProductPageDataWithMerchantData($productAbstractPageSearchTransfer, $productPayloadTransfer->getMerchants());
        }
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $productData): ProductPayloadTransfer
    {
        return $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array $merchantData
     *
     * @return void
     */
    protected function expandProductPageDataWithMerchantData(ProductPageSearchTransfer $productAbstractPageSearchTransfer, array $merchantData): void
    {
        $merchantMapTransfer = new MerchantMapTransfer();

        if (count($merchantData[MerchantMapTransfer::NAMES])) {
            $merchantMapTransfer->setNames($merchantData[MerchantMapTransfer::NAMES]);
        }

        $productAbstractPageSearchTransfer->setMerchantMap($merchantMapTransfer);
    }
}
