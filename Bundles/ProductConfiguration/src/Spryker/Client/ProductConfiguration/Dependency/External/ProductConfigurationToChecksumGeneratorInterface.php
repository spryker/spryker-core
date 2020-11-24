<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\External;

interface ProductConfigurationToChecksumGeneratorInterface
{
    /**
     * @param array $data
     * @param string $encryptionKey
     *
     * @return string
     */
    public function generateChecksum(array $data, string $encryptionKey): string;
}
