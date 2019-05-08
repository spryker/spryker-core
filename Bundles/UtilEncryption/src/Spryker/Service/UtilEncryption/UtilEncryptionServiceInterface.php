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
     * - Generates a random vector with human-readable characters.
     *
     * @api
     *
     * @return string
     */
    public function generateEncryptInitVector(): string;

    /**
     * Specification:
     * - Encrypts given data.
     * - Encodes encrypted data with base64 algorithm.
     *
     * @api
     *
     * @param string $plainText
     * @param string $initVector
     * @param string $encriptionKey
     *
     * @return string
     */
    public function encrypt(string $plainText, string $initVector, string $encriptionKey): string;

    /**
     * Specification:
     * - Decodes encrypted data with base64 algorithm.
     * - Decrypts given data.
     *
     * @api
     *
     * @param string $chiperText
     * @param string $initVector
     * @param string $encriptionKey
     *
     * @return string
     */
    public function decrypt(string $chiperText, string $initVector, string $encriptionKey): string;
}
