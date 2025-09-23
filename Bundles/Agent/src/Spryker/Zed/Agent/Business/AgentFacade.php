<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\FindAgentResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Agent\Business\AgentBusinessFactory getFactory()
 * @method \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface getRepository()
 */
class AgentFacade extends AbstractFacade implements AgentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findAgentByUsername(string $username): FindAgentResponseTransfer
    {
        return $this->getFactory()
            ->createAgentReader()
            ->findAgentByUsername($username);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        return $this->getRepository()->findCustomersByQuery($customerQueryTransfer);
    }
}
