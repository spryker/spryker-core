<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Persistence;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStoragePersistenceFactory getFactory()
 */
interface FileManagerStorageQueryContainerInterface
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
    public function queryFilesByIds($fileIds);

    /**
     * @api
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function queryFileManagerStorage();

    /**
     * @api
     *
     * @param array $fileStorageIds
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorageQuery
     */
    public function queryFileStorageByIds($fileStorageIds);
}
