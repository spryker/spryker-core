<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business;

use Spryker\Zed\FileImportMerchantPortalGui\Business\Importer\MerchantFileImporter;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Importer\MerchantFileImporterInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Reader\MerchantFileImportReader;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Reader\MerchantFileImportReaderInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaver;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaverInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToDataImportFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface getEntityManager()
 */
class FileImportMerchantPortalGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Business\Reader\MerchantFileImportReaderInterface
     */
    public function createMerchantFileImportReader(): MerchantFileImportReaderInterface
    {
        return new MerchantFileImportReader(
            $this->getRepository(),
            $this->getMerchantFileFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaverInterface
     */
    public function createMerchantFileImportSaver(): MerchantFileImportSaverInterface
    {
        return new MerchantFileImportSaver(
            $this->getConfig(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Business\Importer\MerchantFileImporterInterface
     */
    public function createMerchantFileImporter(): MerchantFileImporterInterface
    {
        return new MerchantFileImporter(
            $this->createMerchantFileImportReader(),
            $this->createMerchantFileImportSaver(),
            $this->getDataImportFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeInterface
     */
    public function getMerchantFileFacade(): FileImportMerchantPortalGuiToMerchantFileFacadeInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_FILE);
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToDataImportFacadeInterface
     */
    public function getDataImportFacade(): FileImportMerchantPortalGuiToDataImportFacadeInterface
    {
        return $this->getProvidedDependency(FileImportMerchantPortalGuiDependencyProvider::FACADE_DATA_IMPORT);
    }
}
