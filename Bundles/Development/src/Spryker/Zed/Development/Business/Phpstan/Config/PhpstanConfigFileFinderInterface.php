<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan\Config;

use SplFileInfo;

interface PhpstanConfigFileFinderInterface
{
    /**
     * @param string $directoryPath
     *
     * @return \SplFileInfo|null
     */
    public function searchIn(string $directoryPath): ?SplFileInfo;
}
