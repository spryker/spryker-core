<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\External;

class ProductConfigurationToSprykerChecksumGeneratorAdapter implements ProductConfigurationToChecksumGeneratorInterface
{
    /**
     * @var \Spryker\ChecksumGenerator\Checksum\ChecksumGeneratorInterface
     */
    protected $checksumGenerator;

    /**
     * @param \Spryker\ChecksumGenerator\Checksum\ChecksumGeneratorInterface $checksumGenerator
     */
    public function __construct($checksumGenerator)
    {
        $this->checksumGenerator = $checksumGenerator;
    }

    /**
     * @param array $data
     * @param string $encryptionKey
     *
     * @return string
     */
    public function generateChecksum(array $data, string $encryptionKey): string
    {
        return $this->checksumGenerator->generateChecksum(
            $data,
            $encryptionKey
        );
    }
}
