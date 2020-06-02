<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Mapper;

use Generated\Shared\Transfer\DependencyModuleTransfer;
use Generated\Shared\Transfer\DependencyModuleViewTransfer;

interface DependencyModuleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\DependencyModuleTransfer $dependencyModuleTransfer
     * @param \Generated\Shared\Transfer\DependencyModuleViewTransfer $dependencyModuleViewTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyModuleViewTransfer
     */
    public function mapDependencyModuleTransferToDependencyModuleViewTransfer(
        DependencyModuleTransfer $dependencyModuleTransfer,
        DependencyModuleViewTransfer $dependencyModuleViewTransfer
    ): DependencyModuleViewTransfer;
}
