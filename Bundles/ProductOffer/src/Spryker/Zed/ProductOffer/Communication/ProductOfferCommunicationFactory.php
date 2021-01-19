<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOffer\Communication\Plugin\Checkout\Validator\ProductOfferCheckoutValidator;
use Spryker\Zed\ProductOffer\Communication\Plugin\Checkout\Validator\ProductOfferCheckoutValidatorInterface;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacade getFacade()
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Communication\Plugin\Checkout\Validator\ProductOfferCheckoutValidatorInterface
     */
    public function createProductOfferCheckoutValidator(): ProductOfferCheckoutValidatorInterface
    {
        return new ProductOfferCheckoutValidator($this->getFacade());
    }
}
