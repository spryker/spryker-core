<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PropelOrm\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\FilesToGenerateCollectionTransfer;
use Propel\Generator\Builder\Om\AbstractOMBuilder;
use Propel\Generator\Config\QuickGeneratorConfig;
use Propel\Generator\Model\Table;

class PropelFileHelper extends Module
{
    /**
     * @param \Generated\Shared\Transfer\FilesToGenerateCollectionTransfer $filesToGenerateCollectionTransfer
     * @param \Propel\Generator\Model\Table $table
     *
     * @return void
     */
    public function writePropelFiles(FilesToGenerateCollectionTransfer $filesToGenerateCollectionTransfer, Table $table): void
    {
        $config = new QuickGeneratorConfig();

        foreach ($filesToGenerateCollectionTransfer->getFilesToGenerate() as $fileToGenerateTransfer) {
            $builderClass = $fileToGenerateTransfer->getBuilderClass();
            $builder = new $builderClass($table);
            $builder->setGeneratorConfig($config);
            $this->writePropelFile($builder, $fileToGenerateTransfer->getFileName());
        }
    }

    /**
     * @param \Propel\Generator\Builder\Om\AbstractOMBuilder $objectBuilder
     * @param string $fileName
     *
     * @return void
     */
    protected function writePropelFile(AbstractOMBuilder $objectBuilder, string $fileName): void
    {
        $fileContent = $objectBuilder->build();
        $directory = dirname($fileName);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($fileName, $fileContent);
    }

    /**
     * @param \Generated\Shared\Transfer\FilesToGenerateCollectionTransfer $filesToGenerateCollectionTransfer
     *
     * @return void
     */
    public function deletePropelFiles(FilesToGenerateCollectionTransfer $filesToGenerateCollectionTransfer): void
    {
        foreach ($filesToGenerateCollectionTransfer->getFilesToGenerate() as $fileToGenerateTransfer) {
            $this->deletePropelFile($fileToGenerateTransfer->getFileName());
        }
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    protected function deletePropelFile(string $fileName): void
    {
        unlink($fileName);
    }
}
