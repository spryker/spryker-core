<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationship\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\PriceProductMerchantRelationship\PriceProductMerchantRelationshipFactory getFactory()
 */
class CustomerChangePriceUpdatePlugin extends AbstractPlugin implements CustomerSessionSetPluginInterface
{
    /**
     * @deprecated Please use this plugin only if Yves cart controller doesn't reload the items already.
     *
     * Specification:
     * - Reloads cart items when logged in customer belongs to company and they business unit is assigned to MerchantRelationship.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer): void
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            $this->getFactory()->getCartClient()->reloadItems();
        }
    }
}
