<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;

class PriceProductOfferStorageToPriceProductOfferFacadeBridge implements PriceProductOfferStorageToPriceProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface $priceProductOfferFacade
     */
    public function __construct($priceProductOfferFacade)
    {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): ArrayObject {
        return $this->priceProductOfferFacade->getProductOfferPrices($priceProductOfferCriteriaTransfer);
    }
}
