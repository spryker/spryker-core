<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Dump;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

interface DataImporterAccessFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getDataImporterByType(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface;

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface[]
     */
    public function getDataImporterPlugins(): array;
}
