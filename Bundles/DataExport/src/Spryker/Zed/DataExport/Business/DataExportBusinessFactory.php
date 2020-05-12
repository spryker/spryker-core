<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor;
use Spryker\Zed\DataExport\DataExportDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor
     */
    public function createDataExportHandler(): DataExportExecutor
    {
        return new DataExportExecutor(
            $this->getDataEntityExporterPlugins(),
            $this->getDataExportService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\DataExport\DataExportServiceInterface
     */
    public function getDataExportService(): DataExportServiceInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }

    /**
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface[]
     */
    public function getDataEntityExporterPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_ENTITY_EXPORTER_PLUGINS);
    }
}
