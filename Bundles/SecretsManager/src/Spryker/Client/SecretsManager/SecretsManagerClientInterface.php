<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManager;

use Generated\Shared\Transfer\SecretTransfer;

interface SecretsManagerClientInterface
{
    /**
     * Specification:
     * - Creates a new secret in a secrets manager.
     * - Returns `true` if creation was successful.
     * - Returns `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return bool
     */
    public function createSecret(SecretTransfer $secretTransfer): bool;

    /**
     * Specification:
     * - Retrieves a secret from a secrets manager.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer;
}
