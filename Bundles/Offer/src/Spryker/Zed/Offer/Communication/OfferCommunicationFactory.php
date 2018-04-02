<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication;

use Spryker\Zed\Cart\Business\CartFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Offer\OfferDependencyProvider;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 */
class OfferCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Cart\Business\CartFacadeInterface
     */
    public function getCartFacade(): CartFacadeInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::FACADE_CART);
    }
}
