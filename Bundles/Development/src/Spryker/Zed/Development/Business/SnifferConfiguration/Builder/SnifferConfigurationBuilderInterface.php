<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\SnifferConfiguration\Builder;

interface SnifferConfigurationBuilderInterface
{
    /**
     * @param string $absoluteModulePath
     * @param array $options
     *
     * @return array
     */
    public function getConfiguration(string $absoluteModulePath, array $options = []): array;
}
