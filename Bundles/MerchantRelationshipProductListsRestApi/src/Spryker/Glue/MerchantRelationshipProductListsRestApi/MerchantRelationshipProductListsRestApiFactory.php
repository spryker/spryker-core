<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantRelationshipProductListsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantRelationshipProductListsRestApi\Expander\CustomerExpander;
use Spryker\Glue\MerchantRelationshipProductListsRestApi\Expander\CustomerExpanderInterface;

class MerchantRelationshipProductListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantRelationshipProductListsRestApi\Expander\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander();
    }
}
