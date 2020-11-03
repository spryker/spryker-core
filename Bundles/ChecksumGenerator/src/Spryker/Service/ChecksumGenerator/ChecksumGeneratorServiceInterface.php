<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ChecksumGenerator;

/**
 * @method \Spryker\Service\ChecksumGenerator\ChecksumGeneratorServiceFactory getFactory()
 */
interface ChecksumGeneratorServiceInterface
{
    /**
     * Specification:
     * - Serializes the given data.
     * - Based on serialized data generates checksum.
     * - Converts initialization vector to its binary representation.
     * - Encrypts a checksum using OpenSSL.
     * - Encodes encrypted checksum.
     * - Returns the encoded checksum.
     *
     * @api
     *
     * @param array $data
     * @param string $encryptionKey
     * @param string $hexInitializationVector
     *
     * @return string
     */
    public function generateOpenSslChecksum(array $data, string $encryptionKey, string $hexInitializationVector): string;
}
