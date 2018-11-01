<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataReader\FileResolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\FileResolverFileNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolver;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataReader
 * @group FileResolver
 * @group FileResolverTest
 * Add your own group annotations below this line
 */
class FileResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolveFileThrowsExceptionWhenFileNotFound()
    {
        $this->expectException(FileResolverFileNotFoundException::class);
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName('file_does_not_exist');

        $fileResolver = new FileResolver();
        $fileResolver->resolveFile($dataImporterReaderConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testResolveFileReturnsFilePathIfFileNameIsFile()
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(__FILE__);

        $fileResolver = new FileResolver();
        $this->assertSame(__FILE__, $fileResolver->resolveFile($dataImporterReaderConfigurationTransfer));
    }

    /**
     * @return void
     */
    public function testResolveFileReturnsFilePathIfFileNameFoundInOneDirectory()
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(basename(__FILE__));
        $dataImporterReaderConfigurationTransfer->addDirectory(__DIR__);

        $fileResolver = new FileResolver();
        $this->assertSame(__FILE__, $fileResolver->resolveFile($dataImporterReaderConfigurationTransfer));
    }
}
