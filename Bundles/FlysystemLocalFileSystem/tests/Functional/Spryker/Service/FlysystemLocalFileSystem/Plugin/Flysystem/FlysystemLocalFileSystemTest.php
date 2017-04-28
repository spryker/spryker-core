<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem;

use Codeception\Configuration;
use Generated\Shared\Transfer\FlysystemConfigFtpTransfer;
use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\FilesystemInterface;
use PHPUnit_Framework_TestCase;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\FtpFilesystemBuilderPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group FlysystemLocalFileSystem
 * @group Plugin
 * @group Flysystem
 * @group FlysystemLocalFileSystemTest
 */
class FlysystemLocalFileSystemTest extends PHPUnit_Framework_TestCase
{

    const RESOURCE_FILE_NAME = 'fileName.jpg';

    const FILE_SYSTEM_DOCUMENT = 'customerFileSystem';
    const FILE_SYSTEM_PRODUCT_IMAGE = 'productFileSystem';

    const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    const PATH_DOCUMENT = 'documents/';
    const PATH_PRODUCT_IMAGE = 'images/product/';

    const FILE_DOCUMENT = 'customer.txt';
    const FILE_PRODUCT_IMAGE = 'image.png';

    const FILE_CONTENT = 'Hello World';

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
    public function testFtpFilesystemBuilderPlugin()
    {
        $localFilesystemBuilderPlugin = new FtpFilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigFtpTransfer();
        $adapterConfigTransfer->setHost('ftp://foo.bar');
        $adapterConfigTransfer->setUsername('foo@bar');
        $adapterConfigTransfer->setPassword('foobar');

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('FtpDocumentFilesystem');
        $configTransfer->setType(FtpFilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $ftpFilesystem = $localFilesystemBuilderPlugin->build($configTransfer);

        $this->assertInstanceOf(FilesystemInterface::class, $ftpFilesystem);
    }

}
