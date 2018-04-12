<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Propel\Runtime\Propel;
use Spryker\Service\FileSystem\FileSystemDependencyProvider;
use Spryker\Service\FileSystem\FileSystemService;
use Spryker\Service\FileSystem\FileSystemServiceFactory;
use Spryker\Service\Flysystem\FlysystemDependencyProvider;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemReaderPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemStreamPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Service\Kernel\Container as ServiceContainer;
use Spryker\Zed\FileManager\Business\FileManagerBusinessFactory;
use Spryker\Zed\FileManager\Business\FileManagerFacade;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemBridge;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Zed\FileManager\Stub\FileManagerConfigStub;
use SprykerTest\Zed\FileManager\Stub\FileSystemConfigStub;
use SprykerTest\Zed\FileManager\Stub\FlysystemConfigStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Facade
 * @group FileManagerFacadeTest
 * Add your own group annotations below this line
 */
class FileManagerFacadeTest extends Unit
{
    const PATH_DOCUMENT = 'documents/';
    const FILE_CONTENT = 'Spryker is awesome';
    const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';

    /**
     * @var \Spryker\Zed\FileManager\Business\FileManagerFacade
     */
    protected $facade;

    /**
     * @var string
     */
    protected $testDataFileSystemRootDirectory;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        file_put_contents($this->getDocumentFullFileName('customer_v1.txt'), 'first version of the file');
        file_put_contents($this->getDocumentFullFileName('customer_v2.txt'), 'second version of the file');

        $serviceContainer = new ServiceContainer();
        $serviceContainer = $this->setupContainerAndFlysystemService($serviceContainer);

        $config = new FileSystemConfigStub();
        $factory = new FileSystemServiceFactory();
        $factory->setConfig($config);
        $factory->setContainer($serviceContainer);

        $fileSystemService = new FileSystemService();
        $fileSystemService->setFactory($factory);
        $container = new Container();

        $container[FileManagerDependencyProvider::SERVICE_FILE_SYSTEM] = function (Container $container) use ($fileSystemService) {
            return new FileManagerToFileSystemBridge($fileSystemService);
        };

        $config = new FileManagerConfigStub();
        $factory = new FileManagerBusinessFactory();
        $factory->setContainer($container);
        $factory->setConfig($config);

        $this->facade = new FileManagerFacade();
        $this->facade->setFactory($factory);

        $this->insertDbRecords();
    }

    /**
     * @return void
     */
    protected function resetDb()
    {
        Propel::getConnection()->exec('TRUNCATE TABLE spy_file CASCADE;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_info_pk_seq RESTART WITH 1;');
    }

    /**
     * @return void
     */
    protected function insertDbRecords()
    {
        $this->resetDb();
        $file = new SpyFile();
        $file->setFileName('customer.txt');
        $file->save();
        $file->reload();

        $fileInfo = new SpyFileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setSize(10);
        $fileInfo->setType('text');
        $fileInfo->setVersion(1);
        $fileInfo->setVersionName('v. 1');
        $fileInfo->setStorageFileName('customer_v1.txt');
        $fileInfo->setFileExtension('txt');
        $fileInfo->setCreatedAt('2017-06-06 00:00:00');
        $fileInfo->setUpdatedAt('2017-06-06 00:00:00');
        $fileInfo->save();

        $fileInfo = new SpyFileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setSize(10);
        $fileInfo->setType('text');
        $fileInfo->setVersion(2);
        $fileInfo->setVersionName('v. 2');
        $fileInfo->setStorageFileName('customer_v2.txt');
        $fileInfo->setFileExtension('txt');
        $fileInfo->setCreatedAt('2017-07-07 00:00:00');
        $fileInfo->setUpdatedAt('2017-07-07 00:00:00');
        $fileInfo->save();
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $this->resetDb();
        exec('rm -f ' . $this->getDocumentFullFileName('*'));
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function setupContainerAndFlysystemService(ServiceContainer $container)
    {
        $flysystemContainer = new ServiceContainer();
        $flysystemContainer[FlysystemDependencyProvider::PLUGIN_COLLECTION_FILESYSTEM_BUILDER] = function (ServiceContainer $flysystemContainer) {
            return [
                new LocalFilesystemBuilderPlugin(),
            ];
        };

        $flysystemContainer[FlysystemDependencyProvider::PLUGIN_COLLECTION_FLYSYSTEM] = function (ServiceContainer $flysystemContainer) {
            return [];
        };

        $flysystemConfig = new FlysystemConfigStub();

        $flysystemFactory = new FlysystemServiceFactory();
        $flysystemFactory->setContainer($flysystemContainer);
        $flysystemFactory->setConfig($flysystemConfig);

        $flysystemService = new FlysystemService();
        $flysystemService->setFactory($flysystemFactory);

        $fileSystemReaderPlugin = new FileSystemReaderPlugin();
        $fileSystemReaderPlugin->setService($flysystemService);

        $fileSystemWriterPlugin = new FileSystemWriterPlugin();
        $fileSystemWriterPlugin->setService($flysystemService);

        $fileSystemStreamPlugin = new FileSystemStreamPlugin();
        $fileSystemStreamPlugin->setService($flysystemService);

        $container[FileSystemDependencyProvider::PLUGIN_READER] = function (ServiceContainer $container) use ($fileSystemReaderPlugin) {
            return $fileSystemReaderPlugin;
        };

        $container[FileSystemDependencyProvider::PLUGIN_WRITER] = function (ServiceContainer $container) use ($fileSystemWriterPlugin) {
            return $fileSystemWriterPlugin;
        };

        $container[FileSystemDependencyProvider::PLUGIN_STREAM] = function (ServiceContainer $container) use ($fileSystemStreamPlugin) {
            return $fileSystemStreamPlugin;
        };

        return $container;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getDocumentFullFileName($fileName)
    {
        return Configuration::dataDir() . static::ROOT_DIRECTORY . static::PATH_DOCUMENT . $fileName;
    }

    /**
     * @return void
     */
    public function testReadLatestFileVersion()
    {
        $fileTransfer = $this->facade->readLatestFileVersion(1);
        $this->assertEquals('second version of the file', $fileTransfer->getContent());
        $this->assertEquals('customer.txt', $fileTransfer->getFile()->getFileName());
        $this->assertEquals(1, $fileTransfer->getFile()->getIdFile());
        $this->assertEquals('customer_v2.txt', $fileTransfer->getFileInfo()->getStorageFileName());
        $this->assertEquals('txt', $fileTransfer->getFileInfo()->getFileExtension());
        $this->assertEquals('2', $fileTransfer->getFileInfo()->getVersion());
        $this->assertEquals('v. 2', $fileTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(10, $fileTransfer->getFileInfo()->getSize());
    }

    /**
     * @return void
     */
    public function testRead()
    {
        $fileTransfer = $this->facade->read(1);
        $this->assertEquals('first version of the file', $fileTransfer->getContent());
        $this->assertEquals('customer.txt', $fileTransfer->getFile()->getFileName());
        $this->assertEquals(1, $fileTransfer->getFile()->getIdFile());
        $this->assertEquals('customer_v1.txt', $fileTransfer->getFileInfo()->getStorageFileName());
        $this->assertEquals('txt', $fileTransfer->getFileInfo()->getFileExtension());
        $this->assertEquals('1', $fileTransfer->getFileInfo()->getVersion());
        $this->assertEquals('v. 1', $fileTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(10, $fileTransfer->getFileInfo()->getSize());
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->assertTrue($this->facade->delete(1));
    }

    /**
     * @return void
     */
    public function testDeleteFileInfo()
    {
        $this->assertTrue($this->facade->deleteFileInfo(1));
    }

    /**
     * @return void
     */
    public function testSave()
    {
        $fileInfo = new FileInfoTransfer();
        $fileInfo->setVersionName('v10');
        $fileInfo->setVersion(10);
        $fileInfo->setSize(17);
        $fileInfo->setStorageFileName('new_customer.txt');
        $fileInfo->setType('text');
        $fileInfo->setFileExtension('txt');

        $file = new FileTransfer();
        $file->setFileContent('new customer file');
        $file->setFileName('new_customer.txt');

        $fileManagerSaveRequestTransfer = new FileManagerSaveRequestTransfer();
        $fileManagerSaveRequestTransfer->setContent('new version of the file');
        $fileManagerSaveRequestTransfer->setFile($file);
        $fileManagerSaveRequestTransfer->setFileInfo($fileInfo);

        $savedFileId = $this->facade->save($fileManagerSaveRequestTransfer);
        $this->assertEquals(2, $savedFileId);
    }

    /**
     * @return void
     */
    public function testRollback()
    {
        $this->facade->rollback(1, 1);
        $fileTransfer = $this->facade->readLatestFileVersion(1);
        $this->assertEquals('first version of the file', $fileTransfer->getContent());
        $this->assertEquals('customer.txt', $fileTransfer->getFile()->getFileName());
        $this->assertEquals(1, $fileTransfer->getFile()->getIdFile());
        $this->assertEquals('customer_v1.txt', $fileTransfer->getFileInfo()->getStorageFileName());
        $this->assertEquals('txt', $fileTransfer->getFileInfo()->getFileExtension());
        $this->assertEquals('3', $fileTransfer->getFileInfo()->getVersion());
        $this->assertEquals('v. 3', $fileTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(10, $fileTransfer->getFileInfo()->getSize());
    }
}
