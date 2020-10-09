<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\Service;

interface ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
{
    /**
     * @param array $productConfiguration
     * @param string $encryptionKey
     *
     * @return string
     */
    public function generateProductConfigurationDataChecksum(array $productConfiguration, string $encryptionKey): string;
}
