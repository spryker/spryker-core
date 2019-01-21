<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader;

interface ConfigurationReaderInterface
{
    /**
     * @param string $absoluteModulePath
     *
     * @return array
     */
    public function getModuleConfigurationByAbsolutePath(string $absoluteModulePath): array;
}
