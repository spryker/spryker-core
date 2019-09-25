<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportBusinessFactory getFactory()
 */
class DataImportFacade extends AbstractFacade implements DataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfiguration
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfiguration = null)
    {
        return $this->getFactory()->getImporter()->import($dataImporterConfiguration);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function listImporters(): array
    {
        return $this->getFactory()->createImportDumper()->dump();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function publish(): void
    {
        $this->getFactory()->createDataImporterPublisher()->triggerEvents();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param \Generated\Shared\Transfer\DataSetItemTransfer[] $dataSetItems
     *
     * @return void
     */
    public function writeDataSetItemsToQueue(string $queueName, array $dataSetItems): void
    {
        $this->getFactory()->createQueueWriter()->write($queueName, $dataSetItems);
    }
}
