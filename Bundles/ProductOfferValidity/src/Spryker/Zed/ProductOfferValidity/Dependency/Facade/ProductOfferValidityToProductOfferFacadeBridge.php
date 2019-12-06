<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferTransfer;

class ProductOfferValidityToProductOfferFacadeBridge implements ProductOfferValidityToProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct($productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function activateProductOfferById(int $idProductOffer): ProductOfferTransfer
    {
        return $this->productOfferFacade->activateProductOfferById($idProductOffer);
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function deactivateProductOfferById(int $idProductOffer): ProductOfferTransfer
    {
        return $this->productOfferFacade->deactivateProductOfferById($idProductOffer);
    }
}
