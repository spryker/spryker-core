<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Generated\Shared\Transfer\DependencyCollectionTransfer;

interface ModuleDependencyParserInterface
{
    /**
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function parseOutgoingDependencies(string $module): DependencyCollectionTransfer;
}
