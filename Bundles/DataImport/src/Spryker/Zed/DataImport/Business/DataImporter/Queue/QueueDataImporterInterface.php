<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

use Spryker\Zed\DataImport\Business\DataImporter\DataImporterImportGroupAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;

interface QueueDataImporterInterface extends
    DataImporterInterface,
    DataImporterBeforeImportAwareInterface,
    DataImporterAfterImportAwareInterface,
    DataSetStepBrokerAwareInterface,
    DataImporterDataSetWriterAwareInterface,
    DataImporterImportGroupAwareInterface
{
}
