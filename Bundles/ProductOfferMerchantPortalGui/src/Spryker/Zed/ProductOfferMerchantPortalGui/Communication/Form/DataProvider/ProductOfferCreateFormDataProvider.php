<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

class ProductOfferCreateFormDataProvider extends AbstractProductOfferFormDataProvider implements ProductOfferCreateFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getData(ProductConcreteTransfer $productConcreteTransfer): ProductOfferTransfer
    {
        return $this->addDefaultValues($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function addDefaultValues(
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductOfferTransfer {
        $productOfferTransfer = new ProductOfferTransfer();
        $productOfferTransfer->setConcreteSku($productConcreteTransfer->getSku());
        $productOfferTransfer->setIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
        $productOfferTransfer->setMerchantReference($this->merchantUserFacade->getCurrentMerchantUser()->getMerchantOrFail()->getMerchantReference());
        $productOfferTransfer = $this->setDefaultMerchantStock($productOfferTransfer);

        return $productOfferTransfer;
    }
}
