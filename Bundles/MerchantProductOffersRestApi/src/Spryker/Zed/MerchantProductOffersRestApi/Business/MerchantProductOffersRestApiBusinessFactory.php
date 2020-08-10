<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffersRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper\MerchantProductOfferMapper;
use Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper\MerchantProductOfferMapperInterface;

/**
 * @method \Spryker\Zed\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig getConfig()
 */
class MerchantProductOffersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper\MerchantProductOfferMapperInterface
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapperInterface
    {
        return new MerchantProductOfferMapper();
    }
}
