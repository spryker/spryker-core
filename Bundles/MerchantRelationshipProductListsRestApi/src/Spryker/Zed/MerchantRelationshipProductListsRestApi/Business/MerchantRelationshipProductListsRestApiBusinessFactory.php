<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipProductListsRestApi\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\MerchantRelationshipProductListsRestApi\Business\Expander\CustomerIdentifierExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListsRestApi\MerchantRelationshipProductListsRestApiConfig getConfig()
 */
class MerchantRelationshipProductListsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListsRestApi\Business\Expander\CustomerIdentifierExpanderInterface
     */
    public function createCustomerIdentifierExpander(): CustomerIdentifierExpanderInterface
    {
        return new CustomerIdentifierExpander();
    }
}
