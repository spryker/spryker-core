<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\Decryptor;

interface DecryptorInterface
{
    /**
     * @param string $chiperText
     * @param string $initVector
     * @param string $encryptionKey
     *
     * @return string
     */
    public function decrypt(string $chiperText, string $initVector, string $encryptionKey): string;
}
