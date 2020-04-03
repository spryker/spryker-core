<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

class DataImporterCollectionCollector implements DataImporterCollectionCollectorInterface
{
    /**
     * @var (\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface|array)[]
     */
    protected $dataImporterPlugins;

    /**
     * @param (\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface|array)[] $dataImporterPlugins
     */
    public function __construct(array $dataImporterPlugins)
    {
        $this->dataImporterPlugins = $dataImporterPlugins;
    }

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
    ): DataImporterCollectionInterface {
        foreach ($this->dataImporterPlugins as $dataImporterPlugin) {
            $importerType = is_array($dataImporterPlugin) ? $dataImporterPlugin[0]->getImportType() : $dataImporterPlugin->getImportType();

            if ($importerType === $dataImportConfigurationActionTransfer->getDataEntity()) {
                $dataImporterCollection->addDataImporterPlugins([$dataImporterPlugin]);
            }
        }

        if ($dataImporter) {
            $dataImporterCollection->addDataImporter($dataImporter);
        }

        return $dataImporterCollection;
    }
}
