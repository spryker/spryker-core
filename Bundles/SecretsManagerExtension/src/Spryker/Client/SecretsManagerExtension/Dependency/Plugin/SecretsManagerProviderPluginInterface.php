<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SecretTransfer;

/**
 * Provides the ability to manage secret values.
 *
 * Use this plugin if there is a secret value that needs to be protected.
 */
interface SecretsManagerProviderPluginInterface
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
