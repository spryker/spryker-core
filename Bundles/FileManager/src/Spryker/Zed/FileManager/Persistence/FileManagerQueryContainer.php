<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerPersistenceFactory getFactory()
 */
class FileManagerQueryContainer extends AbstractQueryContainer implements FileManagerQueryContainerInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileWithFileInfoById($idFile)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($idFile);
        $query->leftJoinWithSpyFileInfo();

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileById($idFile)
    {
        $query = $this->getFactory()->createFileQuery();
        $query->filterByIdFile($idFile);

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileInfoByIdFile($idFile = null)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->orderByVersion(Criteria::DESC)
            ->filterByFkFile($idFile);

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileInfo($idFileInfo)
    {
        $query = $this->getFactory()->createFileInfoQuery();
        $query->filterByIdFileInfo($idFileInfo);

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFiles()
    {
        $query = $this->getFactory()->createFileQuery();

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileType()
    {
        $query = $this->getFactory()->createFileTypeQuery();

        return $query;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileDirectoryById($idFileDirectory)
    {
        return $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByIdFileDirectory($idFileDirectory);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileDirectoryNodeById($idFileDirectory)
    {
        return $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByIdFileDirectory($idFileDirectory);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileDirectoryLocalizedAttributesById($idFileDirectoryNodeLocalizedAttributes)
    {
        return $this->getFactory()
            ->createFileDirectoryLocalizedAttributesQuery()
            ->filterByIdFileDirectoryLocalizedAttributes($idFileDirectoryNodeLocalizedAttributes);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryRootFileDirectories()
    {
        return $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByFkParentFileDirectory(null, Criteria::ISNULL)
            ->orderByPosition(Criteria::ASC)
            ->orderByIdFileDirectory(Criteria::ASC);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileDirectoriesByFkParentFileDirectory($fkParentFileDirectoryNode)
    {
        return $this->getFactory()
            ->createFileDirectoryQuery()
            ->filterByFkParentFileDirectory($fkParentFileDirectoryNode)
            ->orderByPosition(Criteria::ASC)
            ->orderByIdFileDirectory(Criteria::ASC);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function queryFileDirectory()
    {
        return $this->getFactory()->createFileDirectoryQuery();
    }
}
