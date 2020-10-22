<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Plugin\Agent;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationFinisherPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface getClient()
 */
class SanitizeCustomerShoppingListsImpersonationFinisherPlugin extends AbstractPlugin implements ImpersonationFinisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes customer shopping list collection from session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function finish(CustomerTransfer $customerTransfer): void
    {
        $this->getClient()->removeShoppingListCollection();
    }
}
