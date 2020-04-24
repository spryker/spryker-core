<?php

namespace Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport;

use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Zed\DataExport\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportFacade getFacade()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class OrderExporterPlugin extends AbstractPlugin implements DataEntityExporterPluginInterface
{
    protected const DATA_ENTITY = 'order';
    protected const SUPPORTED_WRITER = 'csv';

    /**
     * @param array $exportConfiguration
     *
     * @return bool
     */
    public function isApplicable(array $exportConfiguration): bool {
        return
            static::DATA_ENTITY === $exportConfiguration['data_entity'] &&
            static::SUPPORTED_WRITER === $exportConfiguration['writer']['type'];
    }

    /**
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportBatch(array $exportConfiguration): DataExportResultTransfer
    {
        return $this->getFacade()->exportOrderBatch($exportConfiguration);
    }
}
