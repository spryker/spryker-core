<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffersRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper\MerchantProductOfferMapper;

/**
 * @method \Spryker\Zed\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig getConfig()
 */
class MerchantProductOffersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper\MerchantProductOfferMapper
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapper
    {
        return new MerchantProductOfferMapper();
    }
}
