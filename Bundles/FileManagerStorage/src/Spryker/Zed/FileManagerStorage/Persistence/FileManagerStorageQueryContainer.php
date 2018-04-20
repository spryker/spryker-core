<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

use Orm\Zed\FileManagerStorage\Persistence\Map\SpyFileStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStoragePersistenceFactory getFactory()
 */
class FileManagerStorageQueryContainer extends AbstractQueryContainer implements FileManagerStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $fileIds
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFilesByIds($fileIds)
    {
        $query = $this->getFactory()
            ->createFileQuery();
        $query->filterByIdFile($fileIds, Criteria::IN);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function queryFileManagerStorage()
    {
        return $this->getFactory()->createFileManagerStorageQuery();
    }

    /**
     * @api
     *
     * @param array $fileStorageIds
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function queryFileStorageByIds($fileStorageIds)
    {
        $query = $this->getFactory()->createFileStorageQuery();
        $query->filterByFkFile_In($fileStorageIds);
        $query->withColumn(
            "CONCAT(" . SpyFileStorageTableMap::COL_FK_FILE . ", '_', " . SpyFileStorageTableMap::COL_LOCALE . ")",
            FileManagerStorageConstants::STORAGE_COMPOSITE_KEY
        );

        return $query;
    }
}
