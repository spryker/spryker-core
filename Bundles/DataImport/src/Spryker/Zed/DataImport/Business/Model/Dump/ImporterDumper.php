<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Dump;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImportConfigurationTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;

class ImporterDumper implements ImporterDumperInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface
     */
    protected $dataImporterCollection;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\Dump\DataImporterAccessFactoryInterface
     */
    protected $dataImporterAccessFactory;

    /**
     * @var array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface>
     */
    protected $dataImporterPlugins;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $dataImporterCollection
     * @param \Spryker\Zed\DataImport\Business\Model\Dump\DataImporterAccessFactoryInterface $dataImporterAccessFactory
     * @param array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface> $dataImporterPlugins
     */
    public function __construct(
        DataImporterCollectionInterface $dataImporterCollection,
        DataImporterAccessFactoryInterface $dataImporterAccessFactory,
        array $dataImporterPlugins
    ) {
        $this->dataImporterCollection = $dataImporterCollection;
        $this->dataImporterAccessFactory = $dataImporterAccessFactory;
        $this->dataImporterPlugins = $dataImporterPlugins;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumper::getImportersDumpByConfiguration()} instead.
     *
     * @return array<string>
     */
    public function dump(): array
    {
        $appliedDataImporter = $this->getDataImporterFromCollection();

        $dataImporter = [];
        foreach ($appliedDataImporter as $dataImportType => $dataImporterInstance) {
            $dataImporter[$dataImportType] = get_class($dataImporterInstance);
        }

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer $dataImportConfigurationTransfer
     *
     * @return array<string>
     */
    public function getImportersDumpByConfiguration(DataImportConfigurationTransfer $dataImportConfigurationTransfer): array
    {
        $availableImporters = [];
        foreach ($dataImportConfigurationTransfer->getActions() as $dataImportConfigurationActionTransfer) {
            $importerType = $dataImportConfigurationActionTransfer->getDataEntityOrFail();
            $importerClassName = $this->getDataImporterClassNameByImporterType($dataImportConfigurationActionTransfer);
            if ($importerClassName) {
                $availableImporters[$importerType] = $importerClassName;

                continue;
            }

            $importerClassName = $this->getImporterPluginClassNameByImporterType($importerType);
            if ($importerClassName) {
                $availableImporters[$importerType] = $importerClassName;
            }
        }

        ksort($availableImporters);

        return $availableImporters;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return string|null
     */
    protected function getDataImporterClassNameByImporterType(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?string {
        $importer = $this->dataImporterAccessFactory->getDataImporterByType($dataImportConfigurationActionTransfer);

        return $importer ? get_class($importer) : null;
    }

    /**
     * @param string $importerType
     *
     * @return string|null
     */
    protected function getImporterPluginClassNameByImporterType(string $importerType): ?string
    {
        foreach ($this->dataImporterPlugins as $dataImporterPlugin) {
            if ($dataImporterPlugin->getImportType() === $importerType) {
                return get_class($dataImporterPlugin);
            }
        }

        return null;
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Business\Model\DataImporterInterface>
     */
    protected function getDataImporterFromCollection(): array
    {
        $reflection = new ReflectionClass($this->dataImporterCollection);
        $dataImporterProperty = $reflection->getProperty('dataImporter');
        $dataImporterProperty->setAccessible(true);

        return $dataImporterProperty->getValue($this->dataImporterCollection);
    }
}
