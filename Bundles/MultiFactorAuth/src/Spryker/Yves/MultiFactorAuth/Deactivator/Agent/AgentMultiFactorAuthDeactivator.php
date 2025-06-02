<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Deactivator\Agent;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\MultiFactorAuth\Controller\AgentMultiFactorAuthFlowController;
use Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class AgentMultiFactorAuthDeactivator implements AgentMultiFactorAuthDeactivatorInterface
{
    /**
     * @var string
     */
    protected const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var string
     */
    protected const TYPE = 'type';

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
    public function deactivate(Request $request, UserTransfer $userTransfer): void
    {
        $isDeactivation = $this->requestReader->get($request, AgentMultiFactorAuthFlowController::IS_DEACTIVATION);

        $type = $isDeactivation ? $this->requestReader->get($request, static::TYPE_TO_SET_UP) : $request->query->get(static::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
            ->setType($type);

        $this->client->deactivateAgentMultiFactorAuth($multiFactorAuthTransfer);
    }
}
