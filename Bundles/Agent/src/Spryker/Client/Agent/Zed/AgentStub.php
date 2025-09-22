<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Zed;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\FindAgentResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface;

class AgentStub implements AgentStubInterface
{
    public function __construct(protected AgentToZedRequestClientInterface $zedRequestClient)
    {
    }

    public function findAgentByUsername(UserTransfer $userTransfer): FindAgentResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\FindAgentResponseTransfer $findAgentResponseTransfer */
        $findAgentResponseTransfer = $this->zedRequestClient->call('/agent/gateway/find-agent-by-username', $userTransfer);

        return $findAgentResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\Agent\Communication\Controller\GatewayController::findCustomersByQueryAction()
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer $customerAutocompleteResponseTransfer */
        $customerAutocompleteResponseTransfer = $this->zedRequestClient->call('/agent/gateway/find-customers-by-query', $customerQueryTransfer);

        return $customerAutocompleteResponseTransfer;
    }
}
