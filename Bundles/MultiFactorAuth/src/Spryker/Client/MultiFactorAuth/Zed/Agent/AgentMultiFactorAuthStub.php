<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiFactorAuth\Zed\Agent;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
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
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAgentMultiFactorAuthTypes(UserTransfer $userTransfer): MultiFactorAuthTypesCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer */
        $multiFactorAuthTypesCollectionTransfer = $this->zedStub->call('/multi-factor-auth/gateway/get-user-multi-factor-auth-types', $userTransfer);

        return $multiFactorAuthTypesCollectionTransfer;
    }

    /**
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
