<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Communication\Controller;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\FindAgentResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Agent\Business\AgentFacadeInterface getFacade()
 * @method \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    public function findAgentByUsernameAction(UserTransfer $userTransfer): FindAgentResponseTransfer
    {
        return $this->getFacade()
            ->findAgentByUsername($userTransfer->getUsername());
    }

    public function findCustomersByQueryAction(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        return $this->getFacade()
            ->findCustomersByQuery($customerQueryTransfer);
    }
}
