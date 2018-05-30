<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication;

use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileDirectoryFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\MimeTypeFormDataProvider;
use Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Communication\Form\FileLocalizedAttributesForm;
use Spryker\Zed\FileManagerGui\Communication\Form\MimeTypeForm;
use Spryker\Zed\FileManagerGui\Communication\Form\MimeTypeSettingsForm;
use Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoEditTable;
use Spryker\Zed\FileManagerGui\Communication\Table\FileInfoViewTable;
use Spryker\Zed\FileManagerGui\Communication\Table\FileTable;
use Spryker\Zed\FileManagerGui\Communication\Table\MimeTypeSettingsTable;
use Spryker\Zed\FileManagerGui\FileManagerGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\FileManagerGui\Persistence\FileManagerGuiQueryContainerInterface getQueryContainer()
 */
class FileManagerGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param int|null $fileDirectoryId
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileTable
     */
    public function createFileTable($fileDirectoryId = null)
    {
        return new FileTable(
            $this->getFileManagerQueryContainer(),
            $fileDirectoryId
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
     * @return \Spryker\Zed\FileManagerGui\Communication\Table\FileTable
     */
    public function createMimeTypeSettingsTable()
    {
        return new MimeTypeSettingsTable(
            $this->getFileManagerQueryContainer()
        );
    }

    /**
     * @param null|int $idFile
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFileForm($idFile = null)
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
        return new FileFormDataProvider(
            $this->getFileManagerQueryContainer(),
            $this->getLocaleFacade(),
            $this->getFileManagerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\Tabs\FileFormTabs
     */
    public function createFileFormTabs()
    {
        return new FileFormTabs();
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface
     */
    public function getFileManagerFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    public function getFileManagerQueryContainer()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::QUERY_CONTAINER_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FileManagerGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\FileDirectoryFormDataProvider
     */
    public function createFileDirectoryFormDataProvider()
    {
        return new FileDirectoryFormDataProvider(
            $this->getFileManagerQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFileDirectoryForm()
    {
        $dataProvider = $this->createFileDirectoryFormDataProvider();

        return $this->getFormFactory()->create(
            FileDirectoryForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions()
        );
    }

    /**
     * @param int|null $idMimeType
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMimeTypeForm(int $idMimeType = null)
    {
        $dataProvider = $this->createMimeTypeFormDataProvider();

        return $this->getFormFactory()->create(
            MimeTypeForm::class,
            $dataProvider->getData($idMimeType)
        );
    }

    /**
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\DataProvider\MimeTypeFormDataProvider
     */
    public function createMimeTypeFormDataProvider()
    {
        return new MimeTypeFormDataProvider(
            $this->getFileManagerQueryContainer()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMimeTypeSettingsForm()
    {
        return $this->getFormFactory()->create(
            MimeTypeSettingsForm::class
        );
    }
}
