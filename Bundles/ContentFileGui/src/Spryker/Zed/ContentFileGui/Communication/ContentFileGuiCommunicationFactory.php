<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\ContentFileGui\Communication\Form\Constraints\ContentFileListConstraint;
use Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileGuiEditorConfigurationMapper;
use Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileGuiEditorConfigurationMapperInterface;
use Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileListGuiFormDataMapper;
use Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileListGuiFormDataMapperInterface;
use Spryker\Zed\ContentFileGui\Communication\Table\ContentFileListSelectedTable;
use Spryker\Zed\ContentFileGui\Communication\Table\ContentFileListViewTable;
use Spryker\Zed\ContentFileGui\ContentFileGuiDependencyProvider;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToFileManagerFacadeInterface;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentFileGui\ContentFileGuiConfig getConfig()
 */
class ContentFileGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param string|null $identifierSuffix
     *
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\ContentFileListViewTable
     */
    public function createContentFileListViewTable(?string $identifierSuffix = null): ContentFileListViewTable
    {
        return new ContentFileListViewTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierSuffix
        );
    }

    /**
     * @param int[] $fileIds
     * @param string|null $identifierSuffix
     *
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\ContentFileListSelectedTable
     */
    public function createContentFileListSelectedTable(array $fileIds, ?string $identifierSuffix = null): ContentFileListSelectedTable
    {
        return new ContentFileListSelectedTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $fileIds,
            $identifierSuffix
        );
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileGuiEditorConfigurationMapperInterface
     */
    public function createContentFileGuiEditorConfigurationMapper(): ContentFileGuiEditorConfigurationMapperInterface
    {
        return new ContentFileGuiEditorConfigurationMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Communication\Form\Constraints\ContentFileListConstraint
     */
    public function createContentFileListConstraint(): ContentFileListConstraint
    {
        return new ContentFileListConstraint($this->getContentFileFacade());
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Communication\Mapper\ContentFileListGuiFormDataMapperInterface
     */
    public function createContentFileListGuiFormDataMapper(): ContentFileListGuiFormDataMapperInterface
    {
        return new ContentFileListGuiFormDataMapper();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function getFileQueryContainer(): SpyFileQuery
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::PROPEL_QUERY_FILE);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ContentFileGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface
     */
    public function getContentFileFacade(): ContentFileGuiToContentFileFacadeInterface
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::FACADE_CONTENT_FILE);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToFileManagerFacadeInterface
     */
    public function getFileManagerFacade(): ContentFileGuiToFileManagerFacadeInterface
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::FACADE_FILE_MANAGER);
    }
}
