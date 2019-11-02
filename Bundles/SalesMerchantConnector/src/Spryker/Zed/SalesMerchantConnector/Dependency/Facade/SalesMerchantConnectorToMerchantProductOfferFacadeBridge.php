<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferTransfer;

class SalesMerchantConnectorToMerchantProductOfferFacadeBridge implements SalesMerchantConnectorToMerchantProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface
     */
    protected $merchantProductOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface $merchantProductOfferFacade
     */
    public function __construct($merchantProductOfferFacade)
    {
        $this->merchantProductOfferFacade = $merchantProductOfferFacade;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantByProductOfferReference(string $productOfferReference): ?ProductOfferTransfer
    {
        return $this->merchantProductOfferFacade->findMerchantByProductOfferReference($productOfferReference);
    }
}
