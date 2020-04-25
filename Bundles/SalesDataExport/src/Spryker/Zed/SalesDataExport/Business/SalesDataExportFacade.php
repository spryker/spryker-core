<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderItemSequencialExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderSequencialExporter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

/**
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class SalesDataExportFacade extends AbstractFacade implements SalesDataExportFacadeInterface
{
    use BundleConfigResolverAwareTrait;

    /**
     * Specification
     * - Exports orders in various formats (writer)
     * - Returns results of export
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportOrderBatch(array $exportConfiguration): DataExportResultTransfer
    {
        $finalExportConfiguration = $this->getFactory()->getDataExportService()->mergeExportConfigurationByDataEntity(
            $exportConfiguration,
            $this->getFactory()->getDataExportService()->readConfiguration($this->getConfig()->getDefaultExportConfigurationPath())
        );

        $exporter = new OrderSequencialExporter(new OrderReader(), new DataExportService());

        return $exporter->exportBatch($finalExportConfiguration);
    }

    /**
     * Specification
     * - Exports order items in various formats (writer)
     * - Returns results of export
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportOrderItemBatch(array $exportConfiguration): DataExportResultTransfer
    {
        $finalExportConfiguration = $this->getFactory()->getDataExportService()->mergeExportConfigurationByDataEntity(
            $exportConfiguration,
            $this->getFactory()->getDataExportService()->readConfiguration($this->getConfig()->getDefaultExportConfigurationPath())
        );

        $exporter = new OrderItemSequencialExporter(new OrderItemReader(), new DataExportService());

        return $exporter->exportBatch($finalExportConfiguration);
    }
}
