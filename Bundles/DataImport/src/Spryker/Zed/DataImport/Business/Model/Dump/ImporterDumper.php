<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Dump;

use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface;

class ImporterDumper implements ImporterDumperInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface
     */
    protected $dataImporterCollection;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $dataImporterCollection
     */
    public function __construct(DataImporterCollectionInterface $dataImporterCollection)
    {
        $this->dataImporterCollection = $dataImporterCollection;
    }

    /**
     * @return array
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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface[]
     */
    protected function getDataImporterFromCollection(): array
    {
        $reflection = new ReflectionClass($this->dataImporterCollection);
        $dataImporterProperty = $reflection->getProperty('dataImporter');
        $dataImporterProperty->setAccessible(true);

        return $dataImporterProperty->getValue($this->dataImporterCollection);
    }
}
