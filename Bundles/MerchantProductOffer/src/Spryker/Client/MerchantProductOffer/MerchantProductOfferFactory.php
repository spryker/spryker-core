<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOffer;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOffer\Dependency\Client\MerchantProductOfferToZedRequestClientInterface;
use Spryker\Client\MerchantProductOffer\Zed\MerchantProductOfferStub;
use Spryker\Client\MerchantProductOffer\Zed\MerchantProductOfferStubInterface;

class MerchantProductOfferFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOffer\Zed\MerchantProductOfferStubInterface
     */
    public function createMerchantProductOfferStub(): MerchantProductOfferStubInterface
    {
        return new MerchantProductOfferStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\MerchantProductOffer\Dependency\Client\MerchantProductOfferToZedRequestClientInterface
     */
    public function getZedRequestClient(): MerchantProductOfferToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
