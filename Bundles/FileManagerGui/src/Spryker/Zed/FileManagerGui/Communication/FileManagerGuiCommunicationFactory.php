<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication;

use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Communication\Form\FileLocalizedAttributesForm;
use Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoEditTable;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoViewTable;
use Spryker\Zed\FileManagerGui\Communication\Table\FileTable;
use Spryker\Zed\FileManagerGui\FileManagerGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class FileManagerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileTable
     */
    public function createFileTable()
    {
        return new FileTable(
            $this->getFileManagerQueryContainer()
        );
    }

    /**
     * @param int $idFile
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileInfoEditTable
     */
    public function createFileInfoEditTable(int $idFile)
    {
        return new FileInfoEditTable(
            $this->getFileManagerQueryContainer(),
            $idFile
        );
    }

    /**
     * @param int $idFile
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileInfoViewTable
     */
    public function createFileInfoViewTable(int $idFile)
    {
        return new FileInfoViewTable(
            $this->getFileManagerQueryContainer(),
            $idFile
        );
    }

    /**
     * @param \Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider $dataProvider
     * @param null|int $idFile
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileForm($dataProvider, $idFile = null)
    {
        $dataProvider = $this->createFileFormDataProvider();

        return $this->getFormFactory()->create(
            FileForm::class,
            $dataProvider->getData($idFile),
            $dataProvider->getOptions($idFile)
        );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
//        if ($this->currentLocale === null) {
//            $this->currentLocale = $this->getLocaleFacade()
//                ->getCurrentLocale();
//        }
//
//        return $this->currentLocale;

        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\FileLocalizedAttributesForm
     */
    public function createFileLocalizedAttributesForm()
    {
        return new FileLocalizedAttributesForm();
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider
     */
    public function createFileFormDataProvider()
    {
        return new FileFormDataProvider($this->getFileManagerQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs
     */
    public function createFileFormTabs()
    {
        return new FileFormTabs();
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeBridgeInterface
     */
    public function getFileManagerFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface
     */
    public function getFileManagerQueryContainer()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::QUERY_CONTAINER_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeBridgeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_LOCALE);
    }
}
