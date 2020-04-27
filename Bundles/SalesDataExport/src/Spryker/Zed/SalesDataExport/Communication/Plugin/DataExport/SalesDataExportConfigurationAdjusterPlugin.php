<?php

namespace Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport;

use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportConfigurationAdjusterPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportFacade getFacade()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 * @method \Spryker\Zed\SalesDataExport\Communication\SalesDataExportCommunicationFactory getFactory()
 */
class SalesDataExportConfigurationAdjusterPlugin extends AbstractPlugin implements DataExportConfigurationAdjusterPluginInterface
{
    protected const DATA_ENTITIES = ['order', 'order-item', 'order-expense'];

    /**
     * @param array $exportConfiguration
     *
     * @return bool
     */
    public function isApplicable(array $exportConfiguration): bool {
        return in_array($exportConfiguration['data_entity'], static::DATA_ENTITIES, true);
    }

    /**
     * Specification:
     * - Applies Bundles/SalesDataExport/data/exprot/config/sales_export_config.yml on the provided "action export configuration" selectivly by a matching "data_entity"
     *
     * @param array $exportConfiguration
     *
     * @return array
     */
    public function adjustExportConfiguration(array $exportConfiguration): array
    {
        return $this->getFactory()->getDataExportService()->mergeExportConfigurationByDataEntity(
            $exportConfiguration,
            $this->getFactory()->getDataExportService()->readConfiguration($this->getConfig()->getDefaultExportConfigurationPath())
        );
    }
}
