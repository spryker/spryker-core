<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

interface DataImporterCollectionCollectorInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $dataImporterCollection
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null $dataImporter
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface
     */
    public function getDataImporterCollection(
        $dataImporterCollection,
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer,
        ?DataImporterInterface $dataImporter = null
    ): DataImporterCollectionInterface;
}
