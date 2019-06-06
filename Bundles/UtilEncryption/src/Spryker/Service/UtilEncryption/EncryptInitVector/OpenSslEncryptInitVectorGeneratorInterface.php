<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\EncryptInitVector;

interface OpenSslEncryptInitVectorGeneratorInterface
{
    /**
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function generateOpenSslEncryptInitVector(?string $encryptionMethod = null): string;
}
