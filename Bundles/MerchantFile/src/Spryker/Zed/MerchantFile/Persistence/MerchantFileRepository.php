<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFilePersistenceFactory getFactory()
 */
class MerchantFileRepository extends AbstractRepository implements MerchantFileRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer
    {
        $merchantFileCollectionTransfer = $this->getMerchantFileCollection($merchantFileCriteriaTransfer);

        if (!$merchantFileCollectionTransfer->getMerchantFiles()->count()) {
            return null;
        }

        return $merchantFileCollectionTransfer->getMerchantFiles()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer {
        $merchantFileQuery = $this->getFactory()->createMerchantFileQuery();

        if ($merchantFileCriteriaTransfer->getMerchantFileConditions()) {
            $merchantFileQuery = $this->applyMerchantFileConditions(
                $merchantFileCriteriaTransfer->getMerchantFileConditions(),
                $merchantFileQuery,
            );
        }

        $merchantFileEntities = $merchantFileQuery->find();

        return $this->getFactory()
            ->createMerchantFileMapper()
            ->mapEntityCollectionToTransfer(
                $merchantFileEntities,
                new MerchantFileCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileConditionsTransfer $merchantFileConditionsTransfer
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery $merchantFileQuery
     *
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery
     */
    protected function applyMerchantFileConditions(
        MerchantFileConditionsTransfer $merchantFileConditionsTransfer,
        SpyMerchantFileQuery $merchantFileQuery
    ): SpyMerchantFileQuery {
        if ($merchantFileConditionsTransfer->getMerchantFileIds()) {
            $merchantFileQuery->filterByIdMerchantFile_In($merchantFileConditionsTransfer->getMerchantFileIds());
        }

        if ($merchantFileConditionsTransfer->getTypes()) {
            $merchantFileQuery->filterByType_In($merchantFileConditionsTransfer->getTypes());
        }

        if ($merchantFileConditionsTransfer->getUuids()) {
            $merchantFileQuery->filterByUuid_In($merchantFileConditionsTransfer->getUuids());
        }

        if ($merchantFileConditionsTransfer->getUserIds()) {
            $merchantFileQuery->filterByFkUser_In($merchantFileConditionsTransfer->getUserIds());
        }

        return $merchantFileQuery;
    }
}
