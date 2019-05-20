<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\ContentFileGui\Communication\Form\Constraints\FileListConstraint;
use Spryker\Zed\ContentFileGui\Communication\Table\FileSelectedTable;
use Spryker\Zed\ContentFileGui\Communication\Table\FileViewTable;
use Spryker\Zed\ContentFileGui\ContentFileGuiDependencyProvider;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToLocaleInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentFileGui\ContentFileGuiConfig getConfig()
 */
class ContentFileGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param string|null $identifierSuffix
     *
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\FileViewTable
     */
    public function createFileListViewTable(?string $identifierSuffix = null): FileViewTable
    {
        return new FileViewTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierSuffix
        );
    }

    /**
     * @param int[] $fileIds
     * @param string|null $identifierSuffix
     *
     * @return \Spryker\Zed\ContentFileGui\Communication\Table\FileSelectedTable
     */
    public function createFileListSelectedTable(array $fileIds, ?string $identifierSuffix = null): FileSelectedTable
    {
        return new FileSelectedTable(
            $this->getFileQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $identifierSuffix,
            $fileIds
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
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToLocaleInterface
     */
    public function getLocaleFacade(): ContentFileGuiToLocaleInterface
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileInterface
     */
    public function getContentFileFacade(): ContentFileGuiToContentFileInterface
    {
        return $this->getProvidedDependency(ContentFileGuiDependencyProvider::FACADE_CONTENT_FILE);
    }
}
