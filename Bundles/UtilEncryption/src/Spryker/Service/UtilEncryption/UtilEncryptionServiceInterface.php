<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

/**
 * @method \Spryker\Service\UtilEncryption\UtilEncryptionServiceFactory getFactory()
 */
interface UtilEncryptionServiceInterface
{
    /**
     * Specification:
     * - Generates a random vector with human-readable characters for provided OpenSsl encryption method.
     *
     * @api
     *
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function generateOpenSslEncryptInitVector(?string $encryptionMethod = null): string;

    /**
     * Specification:
     * - Encrypts given data using OpenSsl.
     * - Encodes encrypted data with base64 algorithm.
     *
     * @api
     *
     * @param string $plainText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function encryptOpenSsl(string $plainText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string;

    /**
     * Specification:
     * - Decodes encrypted data with base64 algorithm.
     * - Decrypts given data using OpenSsl.
     *
     * @api
     *
     * @param string $cipherText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function decryptOpenSsl(string $cipherText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string;
}
