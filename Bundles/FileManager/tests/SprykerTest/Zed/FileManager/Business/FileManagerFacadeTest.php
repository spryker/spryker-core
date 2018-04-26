<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeNodeTransfer;
use Generated\Shared\Transfer\FileDirectoryTreeTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
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
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceBridge;
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
            return new FileManagerToFileSystemServiceBridge($fileSystemService);
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
        Propel::getConnection()->exec('TRUNCATE TABLE spy_file_directory CASCADE;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_info_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_directory_pk_seq RESTART WITH 1;');
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
        $fileInfo->setExtension('txt');
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
        $fileInfo->setExtension('txt');
        $fileInfo->setCreatedAt('2017-07-07 00:00:00');
        $fileInfo->setUpdatedAt('2017-07-07 00:00:00');
        $fileInfo->save();

        $fileDirectory = new SpyFileDirectory();
        $fileDirectory->setName('first_directory');
        $fileDirectory->setPosition(1);
        $fileDirectory->setIsActive(true);
        $fileDirectory->save();

        $fileDirectory2 = new SpyFileDirectory();
        $fileDirectory2->setName('second_directory');
        $fileDirectory2->setPosition(2);
        $fileDirectory2->setIsActive(true);
        $fileDirectory2->save();

        $fileSubDirectory = new SpyFileDirectory();
        $fileSubDirectory->setName('subdirectory');
        $fileSubDirectory->setIsActive(true);
        $fileSubDirectory->setPosition(1);
        $fileSubDirectory->setParentFileDirectory($fileDirectory);
        $fileSubDirectory->save();
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $this->resetDb();
        exec('rm -rf ' . $this->getDocumentFullFileName('*'));
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
    public function testRead()
    {
        $fileTransfer = $this->facade->readFile(1);
        $this->assertEquals('first version of the file', $fileTransfer->getContent());
        $this->assertEquals('customer.txt', $fileTransfer->getFile()->getFileName());
        $this->assertEquals(1, $fileTransfer->getFile()->getIdFile());
        $this->assertEquals('customer_v1.txt', $fileTransfer->getFileInfo()->getStorageFileName());
        $this->assertEquals('txt', $fileTransfer->getFileInfo()->getExtension());
        $this->assertEquals('1', $fileTransfer->getFileInfo()->getVersion());
        $this->assertEquals('v. 1', $fileTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(10, $fileTransfer->getFileInfo()->getSize());
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->assertTrue($this->facade->deleteFile(1));
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
        $fileInfo->setExtension('txt');

        $file = new FileTransfer();
        $file->setFileContent('new customer file');
        $file->setFileName('new_customer.txt');

        $fileManagerSaveRequestTransfer = new FileManagerSaveRequestTransfer();
        $fileManagerSaveRequestTransfer->setContent('new version of the file');
        $fileManagerSaveRequestTransfer->setFile($file);
        $fileManagerSaveRequestTransfer->setFileInfo($fileInfo);

        $savedFileId = $this->facade->saveFile($fileManagerSaveRequestTransfer);
        $this->assertEquals(2, $savedFileId);
    }

    /**
     * @return void
     */
    public function testRollback()
    {
        $this->facade->rollbackFile(1, 1);
        $fileTransfer = $this->facade->readLatestFileVersion(1);
        $this->assertEquals('first version of the file', $fileTransfer->getContent());
        $this->assertEquals('customer.txt', $fileTransfer->getFile()->getFileName());
        $this->assertEquals(1, $fileTransfer->getFile()->getIdFile());
        $this->assertEquals('customer_v1.txt', $fileTransfer->getFileInfo()->getStorageFileName());
        $this->assertEquals('txt', $fileTransfer->getFileInfo()->getExtension());
        $this->assertEquals('3', $fileTransfer->getFileInfo()->getVersion());
        $this->assertEquals('v.3', $fileTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(10, $fileTransfer->getFileInfo()->getSize());
    }

    /**
     * @return void
     */
    public function testSaveDirectory()
    {
        $fileDirectoryTransfer = new FileDirectoryTransfer();
        $fileDirectoryTransfer->setName('big directory');
        $fileDirectoryTransfer->setIsActive(true);
        $fileDirectoryTransfer->setPosition(1);
        $fileDirectoryId = $this->facade->saveDirectory($fileDirectoryTransfer);
        $this->assertInternalType('int', $fileDirectoryId);

        $file = new FileTransfer();
        $file->setFileContent('big directory file');
        $file->setFileName('big_directory.txt');
        $file->setFkFileDirectory($fileDirectoryId);

        $fileInfo = new FileInfoTransfer();
        $fileInfo->setVersionName('v10');
        $fileInfo->setVersion(10);
        $fileInfo->setSize(17);
        $fileInfo->setStorageFileName('big_directory.txt');
        $fileInfo->setType('text');
        $fileInfo->setExtension('txt');

        $fileManagerSaveRequestTransfer = new FileManagerSaveRequestTransfer();
        $fileManagerSaveRequestTransfer->setContent('new version of the file');
        $fileManagerSaveRequestTransfer->setFile($file);
        $fileManagerSaveRequestTransfer->setFileInfo($fileInfo);

        $fileId = $this->facade->saveFile($fileManagerSaveRequestTransfer);
        $this->assertInternalType('int', $fileId);
        $this->assertFileExists($this->getDocumentFullFileName($fileDirectoryId . '/2-v.1.txt'));
    }

    /**
     * @return void
     */
    public function testFindFileDirectoryTree()
    {
        $tree = $this->facade->findFileDirectoryTree();
        $this->assertInstanceOf(FileDirectoryTreeTransfer::class, $tree);

        foreach($tree->getNodes()->getArrayCopy() as $node) {
            $this->assertInstanceOf(FileDirectoryTreeNodeTransfer::class, $node);
        }
    }

    public function testUpdateFileDirectoryTreeHierarchy()
    {
        $tree = $this->facade->findFileDirectoryTree();

        $firstNode = $tree->getNodes()->getArrayCopy()[0];
        $secondNode = $firstNode->getChildren()->getArrayCopy()[0];
        $thirdNode = $tree->getNodes()->getArrayCopy()[1];

        $this->assertEquals(2, $tree->getNodes()->count());
        $this->assertEquals(1, $firstNode->getChildren()->count());
        $this->assertEquals(0, $secondNode->getChildren()->count());
        $this->assertEquals(0, $thirdNode->getChildren()->count());
        $this->assertEquals(1, $firstNode->getFileDirectory()->getIdFileDirectory());
        $this->assertEquals(3, $secondNode->getFileDirectory()->getIdFileDirectory());
        $this->assertEquals(2, $thirdNode->getFileDirectory()->getIdFileDirectory());

        $firstNode = $tree->getNodes()->getArrayCopy()[0];
        $subNode = $tree->getNodes()->getArrayCopy()[0]->getChildren()[0];
        $thirdNode = $tree->getNodes()->getArrayCopy()[1];
        $subNode->addChild($thirdNode);

        $newFileDirectoryTreeHierarchy = new FileDirectoryTreeTransfer();
        $newFileDirectoryTreeHierarchy->addNode($firstNode);

        $this->facade->updateFileDirectoryTreeHierarchy($newFileDirectoryTreeHierarchy);
        $tree = $this->facade->findFileDirectoryTree();

        $firstNode = $tree->getNodes()->getArrayCopy()[0];
        $secondNode = $firstNode->getChildren()->getArrayCopy()[0];
        $thirdNode = $secondNode->getChildren()->getArrayCopy()[0];

        $this->assertEquals(1, $tree->getNodes()->count());
        $this->assertEquals(1, $firstNode->getChildren()->count());
        $this->assertEquals(1, $secondNode->getChildren()->count());
        $this->assertEquals(0, $thirdNode->getChildren()->count());
        $this->assertEquals(1, $firstNode->getFileDirectory()->getIdFileDirectory());
        $this->assertEquals(3, $secondNode->getFileDirectory()->getIdFileDirectory());
        $this->assertEquals(2, $thirdNode->getFileDirectory()->getIdFileDirectory());
    }

    /**
     * @return void
     */
    public function testDeleteFileDirectory()
    {
        $this->facade->deleteFileDirectory(1);
    }

}
