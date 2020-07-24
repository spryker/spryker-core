<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferValidityTransfer;

class ProductOfferValidityGuiToProductOfferValidityFacadeBridge implements ProductOfferValidityGuiToProductOfferValidityFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityFacadeInterface
     */
    protected $productOfferValidityFacade;

    /**
     * @param \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityFacadeInterface $productOfferValidityFacade
     */
    public function __construct($productOfferValidityFacade)
    {
        $this->productOfferValidityFacade = $productOfferValidityFacade;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer|null
     */
    public function findProductOfferValidityByIdProductOffer(int $idProductOffer): ?ProductOfferValidityTransfer
    {
        return $this->productOfferValidityFacade->findProductOfferValidityByIdProductOffer($idProductOffer);
    }
}
