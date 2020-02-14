<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientInterface;
use Spryker\Client\MerchantSwitcher\MerchantReferenceSwitcher\MerchantReferenceSwitcher;
use Spryker\Client\MerchantSwitcher\MerchantReferenceSwitcher\MerchantReferenceSwitcherInterface;

class MerchantSwitcherFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantSwitcher\MerchantReferenceSwitcher\MerchantReferenceSwitcherInterface
     */
    public function createMerchantReferenceSwitcher(): MerchantReferenceSwitcherInterface
    {
        return new MerchantReferenceSwitcher(
            $this->getMerchantProductOfferClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToMerchantProductOfferClientInterface
     */
    public function getMerchantProductOfferClient(): MerchantSwitcherToMerchantProductOfferClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::CLIENT_MERCHANT_PRODUCT_OFFER);
    }
}
