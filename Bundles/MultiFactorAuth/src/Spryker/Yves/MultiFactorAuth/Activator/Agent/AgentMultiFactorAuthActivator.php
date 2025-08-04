<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Activator\Agent;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\MultiFactorAuth\Controller\AgentMultiFactorAuthFlowController;
use Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class AgentMultiFactorAuthActivator implements AgentMultiFactorAuthActivatorInterface
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $client
     * @param \Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface $requestReader
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $client,
        protected RequestReaderInterface $requestReader
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function activate(Request $request, UserTransfer $userTransfer): void
    {
        $isActivation = $this->requestReader->get($request, AgentMultiFactorAuthFlowController::IS_ACTIVATION);

        $status = $isActivation ? MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION : MultiFactorAuthConstants::STATUS_ACTIVE;
        $type = $isActivation ? $this->requestReader->get($request, AgentMultiFactorAuthFlowController::TYPE_TO_SET_UP) : $request->query->get(MultiFactorAuthTransfer::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setStatus($status)
            ->setType($type);

        $this->client->activateAgentMultiFactorAuth($multiFactorAuthTransfer);
    }
}
