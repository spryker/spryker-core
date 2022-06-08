<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws\Generator;

use Generated\Shared\Transfer\SecretKeyTransfer;

interface SecretGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SecretKeyTransfer $secretKeyTransfer
     *
     * @return string
     */
    public function generateName(SecretKeyTransfer $secretKeyTransfer): string;
}
