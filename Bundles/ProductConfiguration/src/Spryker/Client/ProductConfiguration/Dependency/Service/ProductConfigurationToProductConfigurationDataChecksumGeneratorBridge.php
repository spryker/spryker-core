<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\Service;

class ProductConfigurationToProductConfigurationDataChecksumGeneratorBridge implements ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
{
    /**
     * @var \SprykerSdk\ProductConfigurationSdk\Checksum\ProductConfigurationDataChecksumGeneratorInterface
     */
    protected $productConfigurationDataChecksumGenerator;

    /**
     * @param \SprykerSdk\ProductConfigurationSdk\Checksum\ProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
     */
    public function __construct($productConfigurationDataChecksumGenerator)
    {
        $this->productConfigurationDataChecksumGenerator = $productConfigurationDataChecksumGenerator;
    }

    /**
     * @param array $productConfiguration
     * @param string $encryptionKey
     *
     * @return string
     */
    public function generateProductConfigurationDataChecksum(array $productConfiguration, string $encryptionKey): string
    {
        return $this->productConfigurationDataChecksumGenerator->generateProductConfigurationDataChecksum(
            $productConfiguration,
            $encryptionKey
        );
    }
}
