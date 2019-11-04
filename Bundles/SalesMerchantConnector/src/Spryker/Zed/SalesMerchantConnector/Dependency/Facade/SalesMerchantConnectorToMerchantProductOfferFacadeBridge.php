<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Dependency\Facade;

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
     * @return int|null
     */
    public function findIdMerchantByProductOfferReference(string $productOfferReference): ?int
    {
        return $this->merchantProductOfferFacade->findIdMerchantByProductOfferReference($productOfferReference);
    }
}
