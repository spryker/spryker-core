<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;

interface ComposerDependencyParserInterface
{
    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer
     *
     * @return array
     */
    public function getComposerDependencyComparison(BundleDependencyCollectionTransfer $moduleDependencyCollectionTransfer): array;
}
