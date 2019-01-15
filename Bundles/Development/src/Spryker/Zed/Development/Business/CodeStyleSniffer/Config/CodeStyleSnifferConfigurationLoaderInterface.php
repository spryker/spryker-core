<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer\Config;

interface CodeStyleSnifferConfigurationLoaderInterface
{
    /**
     * @param array $configurationOptions
     * @param string $modulePath
     *
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface
     */
    public function load(array $configurationOptions, string $modulePath): CodeStyleSnifferConfigurationInterface;
}
