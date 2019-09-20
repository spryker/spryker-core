<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\FlysystemLocalFileSystem\Plugin\Flysystem;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\FilesystemInterface;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group FlysystemLocalFileSystem
 * @group Plugin
 * @group Flysystem
 * @group FlysystemLocalFileSystemTest
 * Add your own group annotations below this line
 */
class FlysystemLocalFileSystemTest extends Unit
{
    public const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    public const PATH_DOCUMENT = 'documents/';

    /**
     * @var string
     */
    protected $testDataFlysystemRootDirectory;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->testDataFlysystemRootDirectory = Configuration::dataDir() . static::ROOT_DIRECTORY;
    }

    /**
     * @return void
     */
    public function testLocalFilesystemBuilderPlugin()
    {
        $localFilesystemBuilderPlugin = new LocalFilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigLocalTransfer();
        $adapterConfigTransfer->setRoot($this->testDataFlysystemRootDirectory);
        $adapterConfigTransfer->setPath(static::PATH_DOCUMENT);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('LocalDocumentFilesystem');
        $configTransfer->setType(LocalFilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $localFilesystem = $localFilesystemBuilderPlugin->build($configTransfer);

        $this->assertInstanceOf(FilesystemInterface::class, $localFilesystem);
    }

    /**
     * @return void
     */
    public function testLocalFilesystemBuilderPluginShouldAcceptType()
    {
        $localFilesystemBuilderPlugin = new LocalFilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigLocalTransfer();
        $adapterConfigTransfer->setRoot($this->testDataFlysystemRootDirectory);
        $adapterConfigTransfer->setPath(static::PATH_DOCUMENT);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('LocalDocumentFilesystem');
        $configTransfer->setType(LocalFilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $isTypeAccepted = $localFilesystemBuilderPlugin->acceptType($configTransfer->getType());

        $this->assertTrue($isTypeAccepted);
    }
}
