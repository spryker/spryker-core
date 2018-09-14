<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyContainer;

use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;

interface DependencyContainerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function initialize(ModuleTransfer $moduleTransfer): self;

    /**
     * @param string $module
     * @param string $type
     * @param bool $isOptional
     * @param bool $isTest
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function addDependency(string $module, string $type, bool $isOptional = false, bool $isTest = false): self;

    /**
     * @return \Generated\Shared\Transfer\DependencyCollectionTransfer
     */
    public function getDependencyCollection(): DependencyCollectionTransfer;
}
