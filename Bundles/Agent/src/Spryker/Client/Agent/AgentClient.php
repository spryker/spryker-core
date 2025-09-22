<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Agent\AgentFactory getFactory()
 */
class AgentClient extends AbstractClient implements AgentClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findAgentByUsername(UserTransfer $userTransfer): ?UserTransfer
    {
        return $this->getFactory()
            ->createZedStub()
            ->findAgentByUsername($userTransfer)
            ->getAgent();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function isLoggedIn(): bool
    {
        return $this->getFactory()
            ->createAgentSession()
            ->isLoggedIn();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getAgent(): UserTransfer
    {
        return $this->getFactory()
            ->createAgentSession()
            ->getAgent();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function setAgent(UserTransfer $userTransfer): void
    {
        $this->getFactory()
            ->createAgentSession()
            ->setAgent($userTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function invalidateAgentSession(): void
    {
        $this->getFactory()
            ->createAgentSession()
            ->invalidateAgent();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        return $this->getFactory()
            ->createZedStub()
            ->findCustomersByQuery($customerQueryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function finishImpersonationSession(): void
    {
        $this->getFactory()
            ->createImpersonationSessionFinisher()
            ->finishImpersonationSession();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function applyAgentAccessOnSecuredPattern(string $securedPattern): string
    {
        return $this->getFactory()
            ->createAgentAccessConfigurator()
            ->applyAgentAccessOnSecuredPattern($securedPattern);
    }
}
