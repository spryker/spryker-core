<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Plugin\Agent;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface getClient()
 */
class SanitizeCustomerShoppingListsCustomerImpersonationSanitizerPlugin extends AbstractPlugin implements CustomerImpersonationSanitizerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sanitizes customer shopping list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function sanitize(CustomerTransfer $customerTransfer): void
    {
        $this->getClient()->sanitizeCustomerShoppingListCollection();
    }
}
