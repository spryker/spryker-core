<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication;

use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\DataProvider\MerchantFileImportFormDataProvider;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\DataProvider\MerchantFileImportFormDataProviderInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\MerchantFileImportForm;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\FileImportHistoryGuiTableConfigurationProvider;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\FileImportHistoryGuiTableConfigurationProviderInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\DataProvider\FileImportHistoryGuiTableDataProvider;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface getEntityManager()
 */
class FileImportMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\FileImportHistoryGuiTableConfigurationProviderInterface
     */
    public function createFileImportHistoryGuiTableConfigurationProvider(): FileImportHistoryGuiTableConfigurationProviderInterface
    {
        return new FileImportHistoryGuiTableConfigurationProvider(
            $this->getConfig(),
            $this->getGuiTableFactory(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @param mixed|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantFileImportForm(mixed $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantFileImportForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\DataProvider\MerchantFileImportFormDataProviderInterface
     */
    public function createMerchantFileImportFormDataProvider(): MerchantFileImportFormDataProviderInterface
    {
        return new MerchantFileImportFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createFileImportHistoryGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new FileImportHistoryGuiTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface
     */
    public function getMerchantFileFacade(): FileImportMerchantPortalGuiToMerchantFileFacadeInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_FILE);
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): FileImportMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
