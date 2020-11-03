<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\Service;

class ProductConfigurationToChecksumGeneratorServiceBridge implements ProductConfigurationToChecksumGeneratorServiceInterface
{
    /**
     * @var \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface
     */
    protected $checksumGeneratorService;

    /**
     * @param \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface $checksumGeneratorService
     */
    public function __construct($checksumGeneratorService)
    {
        $this->checksumGeneratorService = $checksumGeneratorService;
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
        return $this->checksumGeneratorService->generateOpenSslChecksum(
            $data,
            $encryptionKey,
            $hexInitializationVector
        );
    }
}
