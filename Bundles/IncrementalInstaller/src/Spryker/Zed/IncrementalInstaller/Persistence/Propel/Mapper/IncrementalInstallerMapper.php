<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer;
use Generated\Shared\Transfer\IncrementalInstallerTransfer;
use Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller;

class IncrementalInstallerMapper
{
    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerTransfer $incrementalInstallerTransfer
     * @param \Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller $incrementalInstallerEntity
     *
     * @return \Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller
     */
    public function mapIncrementalInstallerTransferToIncrementalInstallerEntity(
        IncrementalInstallerTransfer $incrementalInstallerTransfer,
        SpyIncrementalInstaller $incrementalInstallerEntity
    ): SpyIncrementalInstaller {
        return $incrementalInstallerEntity->fromArray($incrementalInstallerTransfer->toArray());
    }

    /**
     * @param array<\Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller> $incrementalInstallerEntities
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer $incrementalInstallerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer
     */
    public function mapIncrementalInstallerEntitiesToIncrementalInstallerCollectionTransfer(
        array $incrementalInstallerEntities,
        IncrementalInstallerCollectionTransfer $incrementalInstallerCollectionTransfer
    ): IncrementalInstallerCollectionTransfer {
        foreach ($incrementalInstallerEntities as $incrementalInstallerEntity) {
            $incrementalInstallerCollectionTransfer->addIncrementalInstaller(
                $this->mapIncrementalInstallerEntityToIncrementalInstallerTransfer(
                    $incrementalInstallerEntity,
                    new IncrementalInstallerTransfer(),
                ),
            );
        }

        return $incrementalInstallerCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\IncrementalInstaller\Persistence\SpyIncrementalInstaller $incrementalInstallerEntity
     * @param \Generated\Shared\Transfer\IncrementalInstallerTransfer $incrementalInstallerTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerTransfer
     */
    protected function mapIncrementalInstallerEntityToIncrementalInstallerTransfer(
        SpyIncrementalInstaller $incrementalInstallerEntity,
        IncrementalInstallerTransfer $incrementalInstallerTransfer
    ): IncrementalInstallerTransfer {
        return $incrementalInstallerTransfer->fromArray($incrementalInstallerEntity->toArray(), true);
    }
}
