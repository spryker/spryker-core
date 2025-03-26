<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataExport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProvider;
use Spryker\Zed\DataExport\Business\DataExportBusinessFactory;
use Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\DataExport\Business\DataExportFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\DataExport\PHPMD)
 */
class DataExportBusinessTester extends Actor
{
    use _generated\DataExportBusinessTesterActions;

    /**
     * @param \Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProvider\DataExportPluginProvider $pluginProviderMock
     *
     * @return \Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor
     */
    public function getDataExportExecutor(DataExportPluginProvider $pluginProviderMock): DataExportExecutor
    {
        $dataExportFactory = new DataExportBusinessFactory();

        return new DataExportExecutor(
            $pluginProviderMock,
            $dataExportFactory->getDataExportService(),
            $dataExportFactory->getConfig(),
            $dataExportFactory->getGracefulRunnerFacade(),
            $dataExportFactory->createDataExportGeneratorExporter(),
        );
    }

    /**
     * @param array $fields
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function getDataExportConfigurationsTransferWithFields(array $fields): DataExportConfigurationsTransfer
    {
        return (new DataExportConfigurationsTransfer())
            ->addAction(
                (new DataExportConfigurationTransfer())
                    ->setDataEntity('testDataEntity')
                    ->setDestination('{timestamp}_{data_entity}.{extension}')
                    ->setFields($fields),
            )
            ->setThrowException(true);
    }

    /**
     * @return @\Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function getDataExportConfigurationsTransferWithoutFields(): DataExportConfigurationsTransfer
    {
        return (new DataExportConfigurationsTransfer())
            ->addAction(
                (new DataExportConfigurationTransfer())
                    ->setDataEntity('testDataEntity')
                    ->setDestination('{timestamp}_{data_entity}.{extension}'),
            )
            ->setThrowException(true);
    }
}
