<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Remover;

use Symfony\Component\Finder\Finder;

interface GeneratedFileFinderInterface
{
    /**
     * @param string $directoryPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directoryPath): Finder;

    /**
     * @param string $directoryPath
     *
     * @return bool
     */
    public function isEmpty(string $directoryPath): bool;
}
