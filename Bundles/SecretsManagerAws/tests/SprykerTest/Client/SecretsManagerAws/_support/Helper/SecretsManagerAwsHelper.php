<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecretsManagerAws\Helper;

use Generated\Shared\DataBuilder\SecretBuilder;
use Generated\Shared\Transfer\SecretTransfer;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;

class SecretsManagerAwsHelper extends AbstractHelper
{
    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function buildSecretTransfer(array $seedData = []): SecretTransfer
    {
        return (new SecretBuilder($seedData))
            ->withSecretKey()
            ->build();
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function buildSecretTransferWithoutSecretKey(array $seedData = []): SecretTransfer
    {
        return (new SecretBuilder($seedData))
            ->build();
    }
}
