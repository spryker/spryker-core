<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\ContentFileGui\Communication\Form\Constraints\FileListConstraint;
use Spryker\Zed\ContentFileGui\Communication\Table\ContentFileSelectedTable;
use Spryker\Zed\ContentFileGui\Communication\Table\ContentFileViewTable;
use Spryker\Zed\ContentFileGui\ContentFileGuiDependencyProvider;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeInterface;
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
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\ContentFileViewTable
     */
    public function createContentFileListViewTable(?string $identifierSuffix = null): ContentFileViewTable
    {
        return new ContentFileViewTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierSuffix
        );
    }

    /**
     * @param int[] $fileIds
     * @param string|null $identifierSuffix
     *
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\ContentFileSelectedTable
     */
    public function createContentFileListSelectedTable(array $fileIds, ?string $identifierSuffix = null): ContentFileSelectedTable
    {
        return new ContentFileSelectedTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $fileIds,
            $identifierSuffix
        );
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Communication\Form\Constraints\FileListConstraint
     */
    public function createContentFileListConstraint(): FileListConstraint
    {
        return new FileListConstraint($this->getContentFileFacade());
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
}
