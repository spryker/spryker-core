<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Symfony\Component\Finder\SplFileInfo;

interface BundleBuilderInterface
{
    /**
     * @param string $baseDirectory
     * @param \Symfony\Component\Finder\SplFileInfo $bundleDirectory
     *
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    public function buildFromDirectory($baseDirectory, SplFileInfo $bundleDirectory);
}
