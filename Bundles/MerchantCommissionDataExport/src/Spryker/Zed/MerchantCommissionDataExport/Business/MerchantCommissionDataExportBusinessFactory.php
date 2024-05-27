<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantCommissionDataExport\Business\Exporter\MerchantCommissionDataExporter;
use Spryker\Zed\MerchantCommissionDataExport\Business\Exporter\MerchantCommissionDataExporterInterface;
use Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface;
use Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig getConfig()
 * @method \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface getRepository()
 */
class MerchantCommissionDataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantCommissionDataExport\Business\Exporter\MerchantCommissionDataExporterInterface
     */
    public function createMerchantCommissionDataExporter(): MerchantCommissionDataExporterInterface
    {
        return new MerchantCommissionDataExporter(
            $this->getRepository(),
            $this->getConfig(),
            $this->getDataExportService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface
     */
    public function getDataExportService(): MerchantCommissionDataExportToDataExportServiceInterface
    {
        return $this->getProvidedDependency(MerchantCommissionDataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }
}
