<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Tree;

use ArrayObject;
use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\FileManager\Persistence\Map\SpyFileDirectoryLocalizedAttributesTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileDirectoryTreeReader implements FileDirectoryTreeReaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $fileManagerQueryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $fileManagerQueryContainer
     */
    public function __construct(FileManagerQueryContainerInterface $fileManagerQueryContainer)
    {
        $this->fileManagerQueryContainer = $fileManagerQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree(LocaleTransfer $localeTransfer = null)
    {
        $this->assertLocaleForRead($localeTransfer);

        return $this->createDirectoryTreeTransfer($localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    protected function assertLocaleForRead(LocaleTransfer $localeTransfer = null)
    {
        if (!$localeTransfer) {
            return;
        }

        $localeTransfer->requireIdLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    protected function createDirectoryTreeTransfer(LocaleTransfer $localeTransfer = null)
    {
        $fileDirectoryTreeTransfer = new FileDirectoryTreeTransfer();

        $rootFileDirectories = $this->findRootFileDirectories();
        $nodesWithoutPosition = new ArrayObject();
        foreach ($rootFileDirectories as $fileDirectoryEntity) {
            $fileDirectoryTreeNodeTransfer = $this->getFileDirectoryTreeNodeRecursively($fileDirectoryEntity, $localeTransfer);
            if ($fileDirectoryEntity->getPosition() === null) {
                $nodesWithoutPosition[] = $fileDirectoryTreeNodeTransfer;

                continue;
            }

            $fileDirectoryTreeTransfer->addNode($fileDirectoryTreeNodeTransfer);
        }

        foreach ($nodesWithoutPosition as $item) {
            $fileDirectoryTreeTransfer->getNodes()->append($item);
        }

        return $fileDirectoryTreeTransfer;
    }

    /**
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRootFileDirectories()
    {
        return $this->fileManagerQueryContainer
            ->queryRootFileDirectories()
            ->find();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $spyFileDirectory
     * @param \Generated\Shared\Transfer\LocaleTransfer|null    $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer
     */
    protected function getFileDirectoryTreeNodeRecursively(SpyFileDirectory $spyFileDirectory, LocaleTransfer $localeTransfer = null)
    {
        $fileDirectoryTreeNodeTransfer = new FileDirectoryTreeNodeTransfer();

        $fileDirectoryTransfer = $this->mapFileDirectoryEntityToTransfer($spyFileDirectory, $localeTransfer);
        $fileDirectoryTreeNodeTransfer->setFileDirectory($fileDirectoryTransfer);

        $childrenFileDirectoryEntities = $this->findChildrenFileDirectories($fileDirectoryTransfer);
        foreach ($childrenFileDirectoryEntities as $childrenFileDirectoryEntity) {
            $childrenFileDirectoryTreeNodeTransfer = $this->getFileDirectoryTreeNodeRecursively($childrenFileDirectoryEntity, $localeTransfer);
            $fileDirectoryTreeNodeTransfer->addChild($childrenFileDirectoryTreeNodeTransfer);
        }

        return $fileDirectoryTreeNodeTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\LocaleTransfer|null    $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    protected function mapFileDirectoryEntityToTransfer(SpyFileDirectory $fileDirectory, LocaleTransfer $localeTransfer = null)
    {
        $fileDirectoryTransfer = new FileDirectoryTransfer();
        $fileDirectoryTransfer->fromArray($fileDirectory->toArray(), true);

        $localizedAttributes = $this->findLocalizedAttributes($fileDirectory, $localeTransfer);
        foreach ($localizedAttributes as $fileDirectoryLocalizedAttributesEntity) {
            $fileDirectoryLocalizedAttributesTransfer = $this->mapFileDirectoryLocalizedAttributesEntityToTransfer($fileDirectoryLocalizedAttributesEntity);
            $fileDirectoryTransfer->addFileDirectoryLocalizedAttribute($fileDirectoryLocalizedAttributesTransfer);
        }

        return $fileDirectoryTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\LocaleTransfer|null    $localeTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findLocalizedAttributes(SpyFileDirectory $fileDirectory, LocaleTransfer $localeTransfer = null)
    {
        $criteria = $this->createLocalizedAttributeFilterCriteria($localeTransfer);

        return $fileDirectory->getSpyFileDirectoryLocalizedAttributess($criteria);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function createLocalizedAttributeFilterCriteria(LocaleTransfer $localeTransfer = null)
    {
        $criteria = new Criteria();

        if ($localeTransfer) {
            $criteria->add(SpyFileDirectoryLocalizedAttributesTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale());
        }

        return $criteria;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes
     *
     * @return \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer
     */
    protected function mapFileDirectoryLocalizedAttributesEntityToTransfer(SpyFileDirectoryLocalizedAttributes $fileDirectoryLocalizedAttributes)
    {
        $fileDirectoryLocalizedAttributesTransfer = new FileDirectoryLocalizedAttributesTransfer();
        $fileDirectoryLocalizedAttributesTransfer->fromArray($fileDirectoryLocalizedAttributes->toArray(), true);

        return $fileDirectoryLocalizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findChildrenFileDirectories(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->fileManagerQueryContainer
            ->queryFileDirectoriesByFkParentFileDirectory($fileDirectoryTransfer->getIdFileDirectory())
            ->find();
    }
}
