<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\Service;

class ProductConfigurationToChecksumGeneratorBridge implements ProductConfigurationToChecksumGeneratorInterface
{
    /**
     * @var \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface
     */
    protected $productConfigurationDataChecksumGenerator;

    /**
     * @param \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface $productConfigurationDataChecksumGenerator
     */
    public function __construct($productConfigurationDataChecksumGenerator)
    {
        $this->productConfigurationDataChecksumGenerator = $productConfigurationDataChecksumGenerator;
    }

    /**
     * @param array $data
     * @param string $encryptionKey
     * @param string $hexInitializationVector
     *
     * @return string
     */
    public function generateOpenSslChecksum(array $data, string $encryptionKey, string $hexInitializationVector): string
    {
        return $this->productConfigurationDataChecksumGenerator->generateOpenSslChecksum(
            $data,
            $encryptionKey,
            $hexInitializationVector
        );
    }
}
