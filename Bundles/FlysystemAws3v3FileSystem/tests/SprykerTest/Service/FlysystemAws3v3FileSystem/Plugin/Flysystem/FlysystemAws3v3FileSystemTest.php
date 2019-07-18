<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer;
use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\FilesystemInterface;
use Spryker\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem\Aws3v3FilesystemBuilderPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group FlysystemAws3v3FileSystem
 * @group Plugin
 * @group Flysystem
 * @group FlysystemAws3v3FileSystemTest
 * Add your own group annotations below this line
 */
class FlysystemAws3v3FileSystemTest extends Unit
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
        if (!class_exists('Aws\S3\S3Client') || !class_exists('League\Flysystem\Adapter\AwsS3v3\AwsS3Adapter')) {
            $this->markTestSkipped('Requires Aws\S3\S3Client and League\Flysystem\Adapter\AwsS3v3\AwsS3Adapter to be installed');
        }

        $localFilesystemBuilderPlugin = new Aws3v3FilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigAws3v3Transfer();
        $adapterConfigTransfer->setRoot($this->testDataFlysystemRootDirectory);
        $adapterConfigTransfer->setPath(static::PATH_DOCUMENT);
        $adapterConfigTransfer->setKey('key');
        $adapterConfigTransfer->setSecret('secret');
        $adapterConfigTransfer->setBucket('bucket');
        $adapterConfigTransfer->setVersion('version');
        $adapterConfigTransfer->setRegion('region');

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $localFilesystem = $localFilesystemBuilderPlugin->build($configTransfer);

        $this->assertInstanceOf(FilesystemInterface::class, $localFilesystem);
    }

    /**
     * @return void
     */
    public function testLocalFilesystemBuilderPluginShouldAcceptType()
    {
        $localFilesystemBuilderPlugin = new Aws3v3FilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigLocalTransfer();
        $adapterConfigTransfer->setRoot($this->testDataFlysystemRootDirectory);
        $adapterConfigTransfer->setPath(static::PATH_DOCUMENT);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $isTypeAccepted = $localFilesystemBuilderPlugin->acceptType($configTransfer->getType());

        $this->assertTrue($isTypeAccepted);
    }
}
