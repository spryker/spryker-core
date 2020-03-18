<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface;

class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface
     */
    public function createMerchantResourceRelationshipExpander(): MerchantResourceRelationshipExpanderInterface
    {
        return new MerchantResourceRelationshipExpander();
    }
}
