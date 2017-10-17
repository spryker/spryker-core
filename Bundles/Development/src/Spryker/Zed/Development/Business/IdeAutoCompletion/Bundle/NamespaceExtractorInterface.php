<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Symfony\Component\Finder\SplFileInfo;

interface NamespaceExtractorInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directory
     * @param string $baseDirectoryGlobPattern
     *
     * @return string
     */
    public function fromDirectory(SplFileInfo $directory, $baseDirectoryGlobPattern);
}
