<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Persistence;

use Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;
use Orm\Zed\IncrementalInstaller\Persistence\Map\SpyIncrementalInstallerTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerPersistenceFactory getFactory()
 */
class IncrementalInstallerRepository extends AbstractRepository implements IncrementalInstallerRepositoryInterface
{
    /**
     * @return array<string>
     */
    public function getExecutedInstallers(): array
    {
        return $this->getFactory()
            ->createIncrementalInstallerPropelQuery()
            ->orderByInstaller()
            ->select([SpyIncrementalInstallerTableMap::COL_INSTALLER])
            ->find()
            ->getData();
    }

    /**
     * @return int
     */
    public function getLastBatch(): int
    {
        /** @var int|null $lastBatch */
        $lastBatch = $this->getFactory()
            ->createIncrementalInstallerPropelQuery()
            ->orderByBatch()
            ->select([SpyIncrementalInstallerTableMap::COL_BATCH])
            ->findOne();

        return $lastBatch ?? 0;
    }

    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer
     */
    public function getIncrementalInstallerCollection(
        IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
    ): IncrementalInstallerCollectionTransfer {
        $incrementalInstallerCollectionTransfer = new IncrementalInstallerCollectionTransfer();
        $incrementalInstallerConditionsTransfer = $incrementalInstallerCriteriaTransfer->getIncrementalInstallerConditions();
        $incrementalInstallerQuery = $this->getFactory()
            ->createIncrementalInstallerPropelQuery();

        if ($incrementalInstallerConditionsTransfer !== null && $incrementalInstallerConditionsTransfer->getBatch() !== null) {
            $incrementalInstallerQuery
                ->filterByBatch($incrementalInstallerConditionsTransfer->getBatch());
        }

        $incrementalInstallerEntities = $incrementalInstallerQuery
            ->find();

        return $this->getFactory()
            ->createIncrementalInstallerMapper()
            ->mapIncrementalInstallerEntitiesToIncrementalInstallerCollectionTransfer(
                $incrementalInstallerEntities->getData(),
                $incrementalInstallerCollectionTransfer,
            );
    }
}
