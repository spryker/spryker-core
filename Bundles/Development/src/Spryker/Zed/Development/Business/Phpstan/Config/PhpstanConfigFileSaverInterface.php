<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan\Config;

interface PhpstanConfigFileSaverInterface
{
    /**
     * @param string $configFilePath
     * @param array<mixed> $data
     *
     * @return void
     */
    public function save(string $configFilePath, array $data): void;
}
