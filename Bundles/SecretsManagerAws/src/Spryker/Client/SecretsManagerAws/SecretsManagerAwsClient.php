<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws;

use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecretsManagerAws\SecretsManagerAwsFactory getFactory()
 */
class SecretsManagerAwsClient extends AbstractClient implements SecretsManagerAwsClientInterface
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
        return $this->getFactory()->createSecretsManagerAwsAdapter()
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
        return $this->getFactory()->createSecretsManagerAwsAdapter()
            ->getSecret($secretTransfer);
    }
}
