<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication;

use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoTable;
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
     * @param bool $editMode
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileInfoTable
     */
    public function createFileInfoTable(int $idFile, bool $editMode = false)
    {
        return new FileInfoTable(
            $this->getFileManagerQueryContainer(),
            $idFile,
            $editMode
        );
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileForm(array $formData = [], array $formOptions = [])
    {
        $fileForm = new FileForm();

        return $this->getFormFactory()->create($fileForm, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider
     */
    public function createFileFormDataProvider()
    {
        return new FileFormDataProvider($this->getFileManagerQueryContainer());
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs
     */
    public function createFileFormTabs()
    {
        return new FileFormTabs();
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer
     */
    public function getFileManagerQueryContainer()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::QUERY_CONTAINER_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_LOCALE);
    }
}
