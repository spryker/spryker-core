<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Plugin\Agent;

use Spryker\Client\AgentExtension\Dependency\Plugin\ImpersonationSessionFinisherPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface getClient()
 */
class SanitizeCustomerShoppingListsImpersonationSessionFinisherPlugin extends AbstractPlugin implements ImpersonationSessionFinisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes customer shopping list collection from session.
     *
     * @api
     *
     * @return void
     */
    public function finish(): void
    {
        $this->getClient()->removeShoppingListCollection();
    }
}
