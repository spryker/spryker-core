<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws\Adapter;

use Generated\Shared\Transfer\SecretTransfer;

interface SecretsManagerAwsAdapterInterface
{
    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return bool
     */
    public function createSecret(SecretTransfer $secretTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer;
}
