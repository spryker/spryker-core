<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\Mapper;

use Generated\Shared\Transfer\DependencyModuleTransfer;
use Generated\Shared\Transfer\DependencyModuleViewTransfer;

class DependencyModuleMapper implements DependencyModuleMapperInterface
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
    ): DependencyModuleViewTransfer {
        [$totalCount, $optionalCount, $testCount] = $this->collectDependencyCount($dependencyModuleTransfer);

        return $dependencyModuleViewTransfer
            ->setTotalDependencyCount($totalCount)
            ->setTestDependencyCount($testCount)
            ->setOptionalDependencyCount($optionalCount);
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyModuleTransfer $dependencyModuleTransfer
     *
     * @return int[]
     */
    protected function collectDependencyCount(
        DependencyModuleTransfer $dependencyModuleTransfer
    ): array {
        $totalCount = 0;
        $optionalCount = 0;
        $testCount = 0;

        foreach ($dependencyModuleTransfer->getDependencies() as $dependencyTransfer) {
            if ($dependencyTransfer->getIsOptional()) {
                $optionalCount++;
            }

            if ($dependencyTransfer->getIsInTest()) {
                $testCount++;
            }

            $totalCount++;
        }

        return [$totalCount, $optionalCount, $testCount];
    }
}
