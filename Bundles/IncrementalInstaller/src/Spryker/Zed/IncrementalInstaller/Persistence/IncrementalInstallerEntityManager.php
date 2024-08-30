<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Persistence;

use Generated\Shared\Transfer\IncrementalInstallerTransfer;
use Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerPersistenceFactory getFactory()
 */
class IncrementalInstallerEntityManager extends AbstractEntityManager implements IncrementalInstallerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerTransfer $incrementalInstallerTransfer
     *
     * @return void
     */
    public function createIncrementalInstaller(IncrementalInstallerTransfer $incrementalInstallerTransfer): void
    {
        /** @var string $installerName */
        $installerName = $incrementalInstallerTransfer->getInstaller();
        /** @var int $batch */
        $batch = $incrementalInstallerTransfer->getBatch();

        $this->getFactory()
            ->createIncrementalInstallerMapper()
            ->mapIncrementalInstallerTransferToIncrementalInstallerEntity($incrementalInstallerTransfer, new SpyIncrementalInstaller())
            ->setInstaller($installerName)
            ->setBatch($batch)
            ->save();
    }
}
