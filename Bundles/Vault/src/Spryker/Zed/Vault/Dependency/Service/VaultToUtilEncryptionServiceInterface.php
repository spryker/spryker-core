<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Dependency\Service;

interface VaultToUtilEncryptionServiceInterface
{
    /**
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function generateOpenSslEncryptInitVector(?string $encryptionMethod = null): string;

    /**
     * @param string $cipherText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function decryptOpenSsl(string $cipherText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string;

    /**
     * @param string $plainText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function encryptOpenSsl(string $plainText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string;
}
