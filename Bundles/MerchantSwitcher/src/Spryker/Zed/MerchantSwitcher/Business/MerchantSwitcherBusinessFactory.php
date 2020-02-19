<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceChecker;
use Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceCheckerInterface;
use Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher\MerchantSwitcher;
use Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher\MerchantSwitcherInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface;
use Spryker\Zed\MerchantSwitcher\MerchantSwitcherDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class MerchantSwitcherBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceCheckerInterface
     */
    public function createMerchantReferenceChecker(): MerchantReferenceCheckerInterface
    {
        return new MerchantReferenceChecker(
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher\MerchantSwitcherInterface
     */
    public function createMerchantSwitcher(): MerchantSwitcherInterface
    {
        return new MerchantSwitcher(
            $this->getMerchantProductOfferFacade(),
            $this->getQuoteFacade(),
            $this->getCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface
     */
    public function getMessengerFacade(): MerchantSwitcherToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToQuoteFacadeInterface
     */
    public function getQuoteFacade(): MerchantSwitcherToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToCartFacadeInterface
     */
    public function getCartFacade(): MerchantSwitcherToCartFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMerchantProductOfferFacadeInterface
     */
    public function getMerchantProductOfferFacade(): MerchantSwitcherToMerchantProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::FACADE_MERCHANT_PRODUCT_OFFER);
    }
}
