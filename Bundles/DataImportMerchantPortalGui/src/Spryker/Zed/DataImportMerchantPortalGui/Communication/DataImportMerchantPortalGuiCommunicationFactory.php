<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication;

use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileForm;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataProvider\DataImportMerchantFileFormDataProvider;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Handler\DataImportMerchantFileHandler;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\DataImportMerchantFileTableConfigurationProvider;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\DataImportMerchantFileTableConfigurationProviderInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\DataProvider\DataImportMerchantFileGuiTableDataProvider;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\Mapper\DataImportMerchantFileGuiTableMapper;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\Mapper\DataImportMerchantFileGuiTableMapperInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReader;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\FileReader;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\FileReaderInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Writer\FileWriter;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Writer\FileWriterInterface;
use Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiDependencyProvider;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToGlossaryFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToFileSystemServiceInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig getConfig()
 */
class DataImportMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\DataImportMerchantFileTableConfigurationProviderInterface
     */
    public function createDataImportMerchantFileTableConfigurationProvider(): DataImportMerchantFileTableConfigurationProviderInterface
    {
        return new DataImportMerchantFileTableConfigurationProvider(
            $this->createDataImportMerchantFileReader(),
            $this->getGuiTableFactory(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDataImportMerchantFileForm(): FormInterface
    {
        $dataProvider = $this->createDataImportMerchantFileFormDataProvider();
        $dataImportMerchantFileTransfer = $dataProvider->getData();

        return $this->getFormFactory()->create(
            DataImportMerchantFileForm::class,
            $dataImportMerchantFileTransfer,
            $dataProvider->getOptions($dataImportMerchantFileTransfer),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataProvider\DataImportMerchantFileFormDataProvider
     */
    public function createDataImportMerchantFileFormDataProvider(): DataImportMerchantFileFormDataProvider
    {
        return new DataImportMerchantFileFormDataProvider(
            $this->getConfig(),
            $this->getMerchantUserFacade(),
            $this->getDataImportMerchantFacade(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createDataImportMerchantFileGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new DataImportMerchantFileGuiTableDataProvider(
            $this->createDataImportMerchantFileGuiTableMapper(),
            $this->createDataImportMerchantFileReader(),
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\GuiTable\Mapper\DataImportMerchantFileGuiTableMapperInterface
     */
    public function createDataImportMerchantFileGuiTableMapper(): DataImportMerchantFileGuiTableMapperInterface
    {
        return new DataImportMerchantFileGuiTableMapper();
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\DataImportMerchantFileReaderInterface
     */
    public function createDataImportMerchantFileReader(): DataImportMerchantFileReaderInterface
    {
        return new DataImportMerchantFileReader(
            $this->getConfig(),
            $this->getDataImportMerchantFacade(),
            $this->getMerchantUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\Reader\FileReaderInterface
     */
    public function createFileReader(): FileReaderInterface
    {
        return new FileReader(
            $this->getFileSystemService(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\Writer\FileWriterInterface
     */
    public function createFileWriter(): FileWriterInterface
    {
        return new FileWriter(
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Handler\DataImportMerchantFileHandler
     */
    public function createDataImportMerchantFileHandler(): DataImportMerchantFileHandler
    {
        return new DataImportMerchantFileHandler(
            $this->getDataImportMerchantFacade(),
            $this->getTranslatorFacade(),
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): DataImportMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): DataImportMerchantPortalGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface
     */
    public function getDataImportMerchantFacade(): DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::FACADE_DATA_IMPORT_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): DataImportMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): DataImportMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Service\DataImportMerchantPortalGuiToFileSystemServiceInterface
     */
    public function getFileSystemService(): DataImportMerchantPortalGuiToFileSystemServiceInterface
    {
        return $this->getProvidedDependency(DataImportMerchantPortalGuiDependencyProvider::SERVICE_FILE_SYSTEM);
    }
}
