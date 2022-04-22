<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManager;

use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecretsManager\SecretsManagerFactory getFactory()
 */
class SecretsManagerClient extends AbstractClient implements SecretsManagerClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return bool
     */
    public function createSecret(SecretTransfer $secretTransfer): bool
    {
        return $this->getFactory()->getSecretsManagerProviderPlugin()
            ->createSecret($secretTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer
    {
        return $this->getFactory()->getSecretsManagerProviderPlugin()
            ->getSecret($secretTransfer);
    }
}
