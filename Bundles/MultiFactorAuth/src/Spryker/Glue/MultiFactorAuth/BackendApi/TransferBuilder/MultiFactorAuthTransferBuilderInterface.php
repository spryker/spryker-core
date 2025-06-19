<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MultiFactorAuthTransferBuilderInterface
{
    /**
     * @param string $multiFactorAuthCode
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function buildMultiFactorAuthCodeTransfer(string $multiFactorAuthCode): MultiFactorAuthCodeTransfer;

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer|null $multiFactorAuthCodeTransfer
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function buildMultiFactorAuthTransfer(
        string $multiFactorAuthType,
        UserTransfer $userTransfer,
        ?MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer = null,
        ?int $status = null
    ): MultiFactorAuthTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function buildUserTransfer(GlueRequestTransfer $glueRequestTransfer): UserTransfer;

    /**
     * @param array<int> $userUuids
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    public function createUserCriteriaTransfer(array $userUuids): UserCriteriaTransfer;
}
