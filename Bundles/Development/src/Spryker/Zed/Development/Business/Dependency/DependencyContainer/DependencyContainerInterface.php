<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyContainer;

use Generated\Shared\Transfer\DependencyCollectionTransfer;

interface DependencyContainerInterface
{
    /**
     * @param string $module
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function initialize(string $module): self;

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
