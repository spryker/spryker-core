<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiFactorAuth\Zed\Agent;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface;

class AgentMultiFactorAuthStub implements AgentMultiFactorAuthStubInterface
{
    /**
     * @param \Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface $zedStub
     */
    public function __construct(protected MultiFactorAuthToZedRequestClientInterface $zedStub)
    {
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::getUserMultiFactorAuthTypesAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAgentMultiFactorAuthTypes(MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer */
        $multiFactorAuthTypesCollectionTransfer = $this->zedStub->call('/multi-factor-auth/gateway/get-user-multi-factor-auth-types', $multiFactorAuthCriteriaTransfer);

        return $multiFactorAuthTypesCollectionTransfer;
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::validateUserMultiFactorAuthStatusAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateAgentMultiFactorAuthStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer */
        $multiFactorAuthValidationResponseTransfer = $this->zedStub->call('/multi-factor-auth/gateway/validate-user-multi-factor-auth-status', $multiFactorAuthValidationRequestTransfer);

        return $multiFactorAuthValidationResponseTransfer;
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::sendUserCodeAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendAgentCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/send-user-code', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::validateUserCodeAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateAgentCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer */
        $multiFactorAuthValidationResponseTransfer = $this->zedStub->call('/multi-factor-auth/gateway/validate-user-code', $multiFactorAuthTransfer);

        return $multiFactorAuthValidationResponseTransfer;
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::activateUserMultiFactorAuthAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function activateAgentMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/activate-user-multi-factor-auth', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuth\Communication\Controller\GatewayController::deactivateUserMultiFactorAuthAction()}
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function deactivateAgentMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer */
        $multiFactorAuthTransfer = $this->zedStub->call('/multi-factor-auth/gateway/deactivate-user-multi-factor-auth', $multiFactorAuthTransfer);

        return $multiFactorAuthTransfer;
    }
}
